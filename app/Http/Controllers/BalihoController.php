<?php

namespace App\Http\Controllers;

use App\Models\Baliho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BalihoController extends Controller
{

    public function landingBalihos(Request $request)
    {
        $query = Baliho::query();

        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }
        if ($request->filled('kabupaten') && $request->kabupaten !== 'SEMUA') {
            $query->where('kabupaten', $request->kabupaten);
        }

        // Landing page ambil semua data (termasuk foto_besar dan path lokasinya)
        $balihos = $query->orderBy('id', 'desc')->get();
        return response()->json($balihos);
    }

    // 2. Ambil 4 Angka Statistik Utama
    public function landingStats()
    {
        return response()->json([
            'total_titik' => Baliho::count(),
            'total_kota'  => Baliho::distinct('kabupaten')->count('kabupaten'),
            'tersedia'    => Baliho::where('status', 'AVAILABLE')->count(),
            'tersewa'     => Baliho::where('status', 'SOLD OUT')->count(),
        ]);
    }

    // 3. Ambil List Provinsi (HANYA YANG ADA DI DATABASE)
    public function activeProvinces()
    {
        $provinces = Baliho::select('provinsi')
                           ->distinct()
                           ->orderBy('provinsi', 'asc')
                           ->pluck('provinsi');

        return response()->json($provinces);
    }

    // 4. Ambil List Kabupaten sesuai Provinsi (HANYA YANG ADA DI DATABASE)
    public function activeKabupaten(Request $request)
    {
        if (!$request->filled('provinsi')) {
            return response()->json([]);
        }

        $kabupaten = Baliho::where('provinsi', $request->provinsi)
                           ->select('kabupaten')
                           ->distinct()
                           ->orderBy('kabupaten', 'asc')
                           ->pluck('kabupaten');

        return response()->json($kabupaten);
    }
    // Ambil semua data untuk Datatable (Sudah dioptimasi dengan Filter Backend)
    public function index(Request $request)
    {
        $query = Baliho::query();

        // Filter berdasarkan Provinsi
        if ($request->filled('provinsi')) {
            $query->where('provinsi', $request->provinsi);
        }

        // Filter berdasarkan Kabupaten (Abaikan jika "SEMUA")
        if ($request->filled('kabupaten') && $request->kabupaten !== 'SEMUA') {
            $query->where('kabupaten', $request->kabupaten);
        }

        // Ambil data tanpa foto_besar agar JSON lebih ringan dan cepat di-load
        $balihos = $query->select('id', 'provinsi', 'kabupaten', 'titik', 'status', 'foto_kecil')
                         ->orderBy('id', 'desc')
                         ->get();

        return response()->json($balihos);
    }

    // Fungsi "Penyapu Bersih" untuk mengubah Base64 lama ke File Fisik
    public function convertSemuaBase64()
    {
        $balihos = Baliho::all();
        $berubah = 0;

        foreach ($balihos as $baliho) {
            // Cek apakah kolom foto_kecil berisi teks Base64
            if (preg_match('/^data:image\/(\w+);base64,/', $baliho->foto_kecil, $type)) {
                $data = substr($baliho->foto_kecil, strpos($baliho->foto_kecil, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, jpeg
                $data = base64_decode($data);

                // Buat nama file unik
                $fileName = Str::random(10) . '_' . time() . '.' . $type;

                // Simpan ke folder storage/app/public/baliho
                Storage::disk('public')->put('baliho/' . $fileName, $data);

                $pathUrl = '/storage/baliho/' . $fileName;

                // Update data di database (timpa base64 dengan link fisik)
                $baliho->update([
                    'foto_kecil' => $pathUrl,
                    'foto_besar' => $pathUrl,
                ]);

                $berubah++;
            }
        }

        return response()->json(['message' => "Selesai bro! $berubah data base64 berhasil di-convert ke file fisik."]);
    }

    // Simpan data baru (Murni Upload File Fisik)
    public function store(Request $request)
    {
        $data = $request->except(['foto']);

        if ($request->hasFile('foto')) {
            // Simpan file secara fisik ke storage
            $path = $request->file('foto')->store('baliho', 'public');
            $data['foto_kecil'] = '/storage/' . $path;
            $data['foto_besar'] = '/storage/' . $path;
        }

        $baliho = Baliho::create($data);
        return response()->json(['success' => true, 'data' => $baliho]);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $baliho = Baliho::findOrFail($id);
        $data = $request->except(['foto']);

        // Kalau user mengupload foto baru saat edit
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('baliho', 'public');
            $data['foto_kecil'] = '/storage/' . $path;
            $data['foto_besar'] = '/storage/' . $path;

            // Opsional: Hapus foto lama dari storage server agar tidak penuh
            if (Str::startsWith($baliho->foto_kecil, '/storage/')) {
                $oldFoto = str_replace('/storage/', '', $baliho->foto_kecil);
                Storage::disk('public')->delete($oldFoto);
            }
        }

        $baliho->update($data);
        return response()->json(['success' => true, 'data' => $baliho]);
    }

    // Ubah status Cepat (Toggle)
    public function toggleStatus($id)
    {
        $baliho = Baliho::findOrFail($id);
        $baliho->status = $baliho->status === 'AVAILABLE' ? 'SOLD OUT' : 'AVAILABLE';
        $baliho->save();
        return response()->json(['success' => true, 'data' => $baliho]);
    }

    // Hapus data
    public function destroy($id)
    {
        $baliho = Baliho::findOrFail($id);

        // Hapus file foto dari storage saat data dihapus
        if (Str::startsWith($baliho->foto_kecil, '/storage/')) {
            $oldFoto = str_replace('/storage/', '', $baliho->foto_kecil);
            Storage::disk('public')->delete($oldFoto);
        }

        $baliho->delete();
        return response()->json(['success' => true]);
    }
}
