<?php

namespace App\Http\Controllers;

use App\Models\Baliho;
use Illuminate\Http\Request;

class BalihoController extends Controller
{
    // Ambil semua data untuk Datatable
    public function index()
    {
        $balihos = Baliho::orderBy('id', 'desc')->get();
        return response()->json($balihos);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $baliho = Baliho::create([
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'titik' => $request->titik,
            'foto_kecil' => $request->foto_kecil,
            'foto_besar' => $request->foto_besar,
            'status' => $request->status,
        ]);
        return response()->json(['success' => true, 'data' => $baliho]);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $baliho = Baliho::findOrFail($id);
        $baliho->update([
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'titik' => $request->titik,
            'foto_kecil' => $request->foto_kecil,
            'foto_besar' => $request->foto_besar,
            'status' => $request->status,
        ]);
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
        Baliho::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
