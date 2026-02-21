<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>Petani Besi - Executive Admin Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        table.dataTable.no-footer { border-bottom: none !important; }
        .dataTables_empty { padding: 3rem !important; text-align: center; color: #6c757d; }
    </style>

    <style>
        :root {
            --primary-red: #D90429;
            --dark-black: #121212;
            --panel-bg: #FFFFFF;
            --app-bg: #F4F6F8;
            --border-light: #E5E7EB;
            --text-muted: #6B7280;
            --success-green: #10B981;
        }

        body {
            background-color: var(--app-bg);
            font-family: 'Inter', sans-serif;
            color: var(--dark-black);
            padding-top: 80px;
            overflow-x: hidden;
        }

        /* --- NAVBAR --- */
        .navbar-admin {
            background: var(--dark-black);
            padding: 15px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-bottom: 3px solid var(--primary-red);
        }
        .navbar-brand { font-weight: 900; color: #fff !important; font-size: 1.5rem; letter-spacing: -0.5px; }
        .navbar-brand span { color: var(--primary-red); }
        .nav-date { color: #fff; font-weight: 700; font-size: 0.9rem; background: rgba(255,255,255,0.1); padding: 8px 18px; border-radius: 50px; }

        /* --- CLICKABLE STAT CARDS --- */
        .stat-card {
            background: var(--panel-bg); border-radius: 16px; padding: 25px;
            border: 2px solid transparent; box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; transition: all 0.3s ease;
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); border-color: #CBD5E1; }
        .stat-card.active-filter { border-color: var(--dark-black); box-shadow: 0 8px 25px rgba(0,0,0,0.1); transform: translateY(-5px); }

        .stat-card.active-filter.card-avail { border-color: var(--success-green); background: #F0FDF4; }
        .stat-card.active-filter.card-sold { border-color: var(--primary-red); background: #FEF2F2; }
        .stat-card.active-filter.card-total { border-color: #3B82F6; background: #EFF6FF; }

        .stat-info p { font-weight: 800; color: var(--text-muted); margin: 0 0 5px 0; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        .stat-info h3 { font-weight: 900; font-size: 2.5rem; margin: 0; line-height: 1; }
        .icon-box { width: 60px; height: 60px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
        .bg-total { background: #E0F2FE; color: #3B82F6; }
        .bg-avail { background: #D1FAE5; color: var(--success-green); }
        .bg-sold { background: #FEE2E2; color: var(--primary-red); }

        /* --- PANELS --- */
        .admin-panel { background: var(--panel-bg); border-radius: 16px; padding: 25px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 25px; }
        .panel-header { font-weight: 900; font-size: 1.1rem; border-bottom: 2px solid var(--app-bg); padding-bottom: 15px; margin-bottom: 20px; color: var(--dark-black); text-transform: uppercase; letter-spacing: 0.5px; display: flex; justify-content: space-between; align-items: center; }

        /* --- CEK STATISTIK FULL WIDTH BAR --- */
        .stats-bar-wrapper { background: var(--panel-bg); border-radius: 16px; padding: 20px 25px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 25px; display: flex; align-items: center; gap: 20px; }
        .stats-bar-title { font-weight: 900; font-size: 1rem; text-transform: uppercase; color: var(--dark-black); display: flex; align-items: center; white-space: nowrap; }
        .stats-bar-title i { color: #3B82F6; font-size: 1.2rem; margin-right: 10px; }

        /* --- UPLOAD AREA --- */
        .upload-area {
            border: 2px dashed #CBD5E1; border-radius: 12px; padding: 30px 20px;
            text-align: center; cursor: pointer; transition: 0.3s; background: #F8FAFC;
            position: relative; overflow: hidden;
        }
        .upload-area:hover { border-color: var(--success-green); background: #F0FDF4; }
        .upload-placeholder i { font-size: 2.5rem; color: #94A3B8; margin-bottom: 10px; }
        .upload-placeholder p { margin: 0; font-weight: 700; font-size: 0.85rem; color: var(--text-muted); }
        .image-preview { width: 100%; height: 180px; object-fit: cover; border-radius: 8px; display: none; }
        .file-input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

        /* --- FORM ELEMENTS & GREEN BUTTON --- */
        .form-label { font-weight: 800; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid var(--border-light); padding: 12px 15px; font-weight: 600; font-size: 0.95rem; background: #FAFAFA; }
        .form-control:focus, .form-select:focus { border-color: var(--success-green); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); background: #fff; }

        .btn-green { background: var(--success-green); color: #fff; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; padding: 14px; border-radius: 8px; border: none; transition: 0.3s; }
        .btn-green:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3); color: #fff; }

        .btn-primary-custom { background: #3B82F6; color: #fff; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; padding: 14px; border-radius: 8px; border: none; transition: 0.3s; }
        .btn-primary-custom:hover { background: #2563EB; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); color: #fff; }

        /* --- TABLE ELEGANT --- */
        .table-container { max-height: 600px; overflow-y: auto; padding-right: 5px; }
        .table-container::-webkit-scrollbar { width: 6px; }
        .table-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .table th { position: sticky; top: 0; background: var(--panel-bg); font-weight: 800; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid var(--border-light); padding: 15px 10px; z-index: 2; }
        .table td { vertical-align: middle; padding: 15px 10px; border-bottom: 1px solid var(--border-light); font-weight: 600; font-size: 0.95rem; }
        .tb-img { width: 100px; height: 70px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .badge-status { padding: 6px 14px; border-radius: 6px; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; }
        .badge-status.avail { background: #D1FAE5; color: var(--success-green); }
        .badge-status.sold { background: #FEE2E2; color: var(--primary-red); }

        .btn-action { width: 38px; height: 38px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--border-light); background: #fff; color: var(--text-muted); transition: 0.2s; cursor: pointer; }
        .btn-action:hover { background: var(--app-bg); color: var(--dark-black); }
        .btn-action.edit:hover { border-color: #3B82F6; color: #3B82F6; background: #EFF6FF; }
        .btn-action.delete:hover { border-color: var(--primary-red); color: var(--primary-red); background: #FEF2F2; }
        .btn-action.toggle:hover { border-color: var(--success-green); color: var(--success-green); background: #F0FDF4; }

        /* --- PAGINATION & DATA LIMIT --- */
        .pagination-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-light); }
        .data-limit-select { padding: 8px 15px; border-radius: 8px; border: 1px solid var(--border-light); font-weight: 700; font-size: 0.85rem; color: var(--dark-black); outline: none; background: #fff; cursor: pointer; }
        .pagination-btn { padding: 8px 16px; border: 1px solid var(--border-light); background: #fff; color: var(--dark-black); font-weight: 700; font-size: 0.85rem; border-radius: 6px; transition: 0.2s; margin: 0 3px; cursor: pointer; }
        .pagination-btn:hover:not(:disabled) { background: var(--app-bg); }
        .pagination-btn.active { background: var(--dark-black); color: #fff; border-color: var(--dark-black); }
        .pagination-btn:disabled { color: #A1A1AA; cursor: not-allowed; background: #F4F4F5; }
        .page-info-text { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); }

        /* --- CUSTOM MODAL (POP UP) --- */
        .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .modal-header { border-bottom: 2px solid var(--app-bg); padding: 20px 25px; }
        .modal-title { font-weight: 900; font-size: 1.2rem; text-transform: uppercase; }
        .modal-body { padding: 25px; }

        /* Swol Style Override */
        .swal2-popup { font-family: 'Inter', sans-serif; border-radius: 16px !important; }
        .swal2-title { font-weight: 900 !important; font-size: 1.5rem !important; }
        .swal2-html-container { font-weight: 500; font-size: 1rem; color: #555; }
        .swal-highlight { font-weight: 800; color: var(--dark-black); font-size: 1.2rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top navbar-admin">
        <div class="container-fluid px-5">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-server me-2"></i>PETANI BESI <span>ADMIN</span></a>
            <div class="d-flex align-items-center gap-3">
                <div class="nav-date" id="live-date">Memuat Tanggal...</div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger fw-bold rounded-pill px-3">
                        <i class="fa-solid fa-power-off me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-5 py-4">

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card active-filter card-total" onclick="setFilter('ALL')" id="card-ALL">
                    <div class="stat-info"><p>Total Inventori</p><h3 id="stat-total">0</h3></div>
                    <div class="icon-box bg-total"><i class="fa-solid fa-layer-group"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card card-avail" onclick="setFilter('AVAILABLE')" id="card-AVAILABLE">
                    <div class="stat-info"><p>Baliho Tersedia</p><h3 id="stat-available" style="color: var(--success-green);">0</h3></div>
                    <div class="icon-box bg-avail"><i class="fa-solid fa-check-double"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card card-sold" onclick="setFilter('SOLD OUT')" id="card-SOLD">
                    <div class="stat-info"><p>Baliho Tersewa</p><h3 id="stat-soldout" style="color: var(--primary-red);">0</h3></div>
                    <div class="icon-box bg-sold"><i class="fa-solid fa-ban"></i></div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="stats-bar-wrapper">
                    <div class="stats-bar-title">
                        <i class="fa-solid fa-magnifying-glass-chart"></i> Cek Area Khusus
                    </div>
                    <div class="flex-grow-1">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select class="form-select prov-select fw-bold" id="cek-provinsi">
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select kab-select fw-bold" id="cek-kabupaten" disabled>
                                    <option value="">-- Semua Kota/Kab --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-dark w-100 fw-bold border-0" id="btn-cek-stats" style="padding: 12px; border-radius: 8px;">
                                    <i class="fa-solid fa-bolt me-2"></i> Tampilkan Laporan Area
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-4">
                <div class="admin-panel sticky-top" style="top: 100px; z-index: 1;">
                    <div class="panel-header" style="border-bottom: 2px solid var(--border-light);">
                        <span><i class="fa-solid fa-square-plus me-2 text-success"></i> Form Input Baliho</span>
                    </div>

                    <form id="form-tambah">
                        <input type="hidden" id="add-base64">

                        <div class="mb-4">
                            <label class="form-label">Upload Foto Lokasi *</label>
                            <div class="upload-area">
                                <input type="file" class="file-input" id="file-add" accept="image/png, image/jpeg, image/jpg" required>
                                <div class="upload-placeholder" id="placeholder-add">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p>Klik / Tarik foto ke sini</p>
                                </div>
                                <img src="" id="preview-add" class="image-preview" alt="Preview">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Provinsi *</label>
                                <select class="form-select prov-select" id="add-provinsi" required>
                                    <option value="">Memuat...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kabupaten *</label>
                                <select class="form-select kab-select" id="add-kabupaten" disabled required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Jalan / Titik *</label>
                            <input type="text" class="form-control" id="add-titik" placeholder="Contoh: Jl. Ring Road Utara" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status Awal *</label>
                            <select class="form-select fw-bold" id="add-status" required>
                                <option value="AVAILABLE" style="color: var(--success-green);">AVAILABLE (Tersedia)</option>
                                <option value="SOLD OUT" style="color: var(--primary-red);">SOLD OUT (Tersewa)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-green w-100">
                            <i class="fa-solid fa-database me-2"></i> TAMBAH DATA BARU
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="admin-panel">
                    <div class="panel-header border-0 mb-0 pb-0">
                        <span><i class="fa-solid fa-list me-2"></i> Database Inventori Utama</span>
                        <div class="input-group" style="width: 300px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 8px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" id="search-data" class="form-control border-start-0 ps-0" placeholder="Cari kota / nama jalan...">
                        </div>
                    </div>

                    <div class="my-3 py-2 px-3 bg-light rounded-3 fw-bold text-muted d-flex justify-content-between align-items-center" style="font-size: 0.85rem;" id="filter-indicator">
                        <div><i class="fa-solid fa-filter me-2 text-danger"></i> Menampilkan: <span class="text-dark">SEMUA BALIHO</span></div>
                        <span class="badge bg-dark" id="count-table" style="font-size:0.75rem; padding: 6px 10px;">0 Data Terfilter</span>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <span class="text-muted fw-bold me-2" style="font-size:0.85rem;"><i class="fa-solid fa-table-list me-1"></i>Tampilkan:</span>
                        <select id="data-limit" class="data-limit-select">
                            <option value="10">10 Baris</option>
                            <option value="25">25 Baris</option>
                            <option value="50">50 Baris</option>
                            <option value="100">100 Baris</option>
                        </select>
                    </div>

                    <div class="table-container">
                        <table class="table table-hover" id="main-table" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="15%">Visual</th>
                                    <th width="45%">Informasi Lokasi</th>
                                    <th width="20%">Status</th>
                                    <th width="20%" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-data">
                                </tbody>
                        </table>
                    </div>

                    <div class="pagination-container">
                        <div class="page-info-text" id="page-info">
                            Menampilkan 0 - 0 dari 0 data
                        </div>
                        <div id="pagination-controls">
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Data Baliho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-edit">
                        <input type="hidden" id="edit-id">
                        <input type="hidden" id="edit-base64">

                        <div class="mb-4">
                            <label class="form-label">Ganti Foto Lokasi (Opsional)</label>
                            <div class="upload-area" style="padding: 10px;">
                                <input type="file" class="file-input" id="file-edit" accept="image/png, image/jpeg, image/jpg">
                                <img src="" id="preview-edit" class="image-preview" alt="Preview" style="display:block;">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select prov-select" id="edit-provinsi" required></select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kabupaten</label>
                                <select class="form-select kab-select" id="edit-kabupaten" required></select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Jalan / Titik</label>
                            <input type="text" class="form-control" id="edit-titik" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status</label>
                            <select class="form-select fw-bold" id="edit-status" required>
                                <option value="AVAILABLE" style="color: var(--success-green);">AVAILABLE (Tersedia)</option>
                                <option value="SOLD OUT" style="color: var(--primary-red);">SOLD OUT (Tersewa)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100"><i class="fa-solid fa-save me-2"></i> Update Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        // Setup CSRF Token untuk semua AJAX Request ke Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            $('#live-date').text(new Date().toLocaleDateString('id-ID', dateOptions));

            let database = []; // Sekarang dikosongkan karena akan ambil dari MySQL

            // ==========================================
            // 1. SETUP DATATABLES INVISIBLE
            // ==========================================
            let dataTable = $('#main-table').DataTable({
                data: database,
                dom: 't',
                autoWidth: false,
                ordering: false,
                pageLength: 10,
                language: {
                    emptyTable: `<div class="text-center py-5 text-muted"><i class="fa-solid fa-folder-open fs-1 mb-2 d-block"></i>Data belum tersedia.</div>`,
                    zeroRecords: `<div class="text-center py-5 text-muted"><i class="fa-solid fa-magnifying-glass fs-1 mb-2 d-block"></i>Data tidak ditemukan.</div>`
                },
                columns: [
                    {
                        data: null,
                        width: "15%",
                        render: function(data, type, row) {
                            return `<img src="${row.foto_kecil}" class="tb-img" alt="Foto">`;
                        }
                    },
                    {
                        data: null,
                        width: "45%",
                        render: function(data, type, row) {
                            return `
                                <div class="fw-bold mb-1" style="font-size: 1.05rem; color: #111;">${row.titik}</div>
                                <div class="text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-location-dot text-danger me-1"></i> ${row.kabupaten}, ${row.provinsi}</div>
                            `;
                        }
                    },
                    {
                        data: "status",
                        width: "20%",
                        render: function(data, type, row) {
                            const badgeClass = data === "AVAILABLE" ? "avail" : "sold";
                            return `<span class="badge-status ${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: null,
                        width: "20%",
                        className: "text-end",
                        render: function(data, type, row) {
                            return `
                                <button type="button" class="btn-action toggle me-1" title="Ubah Status Cepat" onclick="toggleStatus(${row.id})"><i class="fa-solid fa-rotate"></i></button>
                                <button type="button" class="btn-action edit me-1" title="Edit Data" onclick="openEdit(${row.id})"><i class="fa-solid fa-pen"></i></button>
                                <button type="button" class="btn-action delete" title="Hapus Data" onclick="hapusData(${row.id})"><i class="fa-solid fa-trash"></i></button>
                            `;
                        }
                    }
                ]
            });

            // Update UI Pagination & Header setiap kali Datatables render
            dataTable.on('draw', function() {
                let info = dataTable.page.info();
                $('#count-table').text(`${info.recordsDisplay} Data Terfilter`);
                let startItemCount = info.recordsDisplay === 0 ? 0 : info.start + 1;
                let endItemCount = info.end;
                $('#page-info').text(`Menampilkan ${startItemCount} - ${endItemCount} dari ${info.recordsDisplay} data`);

                const paginationBox = $('#pagination-controls').empty();
                let totalPages = info.pages;
                let currentPage = info.page + 1;

                if (totalPages > 1) {
                    paginationBox.append(`<button class="pagination-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})"><i class="fa-solid fa-chevron-left"></i></button>`);
                    for(let i = 1; i <= totalPages; i++) {
                        if (totalPages > 5) {
                            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                                const activeClass = i === currentPage ? 'active' : '';
                                paginationBox.append(`<button class="pagination-btn ${activeClass}" onclick="changePage(${i})">${i}</button>`);
                            } else if (i === currentPage - 2 || i === currentPage + 2) {
                                paginationBox.append(`<button class="pagination-btn" disabled>...</button>`);
                            }
                        } else {
                            const activeClass = i === currentPage ? 'active' : '';
                            paginationBox.append(`<button class="pagination-btn ${activeClass}" onclick="changePage(${i})">${i}</button>`);
                        }
                    }
                    paginationBox.append(`<button class="pagination-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})"><i class="fa-solid fa-chevron-right"></i></button>`);
                }
            });

            // Update angka-angka besar di atas
            function updateTopStatCards() {
                $('#stat-total').text(database.length);
                $('#stat-available').text(database.filter(i => i.status === "AVAILABLE").length);
                $('#stat-soldout').text(database.filter(i => i.status === "SOLD OUT").length);
            }

            // Fungsi utama untuk memanggil data MySQL via API (GET)
            function fetchBalihos() {
                $.get('/api/balihos', function(data) {
                    database = data;
                    dataTable.clear();
                    dataTable.rows.add(database);
                    dataTable.draw();
                    updateTopStatCards();
                });
            }

            // Eksekusi load awal
            fetchBalihos();

            // Link Dropdown Baris ke DataTables
            $('#data-limit').change(function() {
                dataTable.page.len($(this).val()).draw();
            });

            // Link Kolom Pencarian ke DataTables
            $('#search-data').on('keyup', function() {
                dataTable.search(this.value).draw();
            });

            window.changePage = function(page) {
                dataTable.page(page - 1).draw('page');
            };

            window.setFilter = function(filterType) {
                $('.stat-card').removeClass('active-filter');
                if(filterType === 'ALL') $('#card-ALL').addClass('active-filter');
                if(filterType === 'AVAILABLE') $('#card-AVAILABLE').addClass('active-filter');
                if(filterType === 'SOLD OUT') $('#card-SOLD').addClass('active-filter');

                let text = filterType === 'ALL' ? "SEMUA BALIHO" : (filterType === 'AVAILABLE' ? "BALIHO TERSEDIA SAJA" : "BALIHO TERSEWA SAJA");
                $('#filter-indicator span.text-dark').text(text);

                if (filterType === 'ALL') {
                    dataTable.column(2).search('').draw();
                } else {
                    dataTable.column(2).search('^' + filterType + '$', true, false).draw();
                }
            };

            // ==========================================
            // 2. FETCH API EMSIFA (Provinsi & Kabupaten)
            // ==========================================
            const apiProv = 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json';
            fetch(apiProv).then(r => r.json()).then(data => {
                let html = '<option value="">-- Pilih Provinsi --</option>';
                data.forEach(p => html += `<option value="${p.name}" data-id="${p.id}">${p.name}</option>`);
                $('.prov-select').html(html);
            });

            $('.prov-select').change(function() {
                const parentId = $(this).attr('id');
                const provId = $(this).find(':selected').data('id');
                let targetKab = $('#add-kabupaten');
                if (parentId === 'edit-provinsi') targetKab = $('#edit-kabupaten');
                if (parentId === 'cek-provinsi') targetKab = $('#cek-kabupaten');

                if(provId) {
                    targetKab.html('<option value="">Memuat...</option>').prop('disabled', true);
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
                        .then(r => r.json()).then(data => {
                            let html = '<option value="SEMUA">-- Semua Kota/Kab --</option>';
                            data.forEach(k => html += `<option value="${k.name}">${k.name}</option>`);
                            targetKab.html(html).prop('disabled', false);
                        });
                } else {
                    targetKab.html('<option value="">-- Semua Kota/Kab --</option>').prop('disabled', true);
                }
            });

            // ==========================================
            // 3. STATISTIK AREA (Pop-up)
            // ==========================================
            $('#btn-cek-stats').click(function() {
                const prov = $('#cek-provinsi').val();
                const kab = $('#cek-kabupaten').val();

                if(!prov) return Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Pilih provinsi dulu bro buat ngecek data.' });

                let dataCek = database.filter(i => i.provinsi === prov);
                let textLokasi = prov;

                if(kab && kab !== "SEMUA") {
                    dataCek = dataCek.filter(i => i.kabupaten === kab);
                    textLokasi = `${kab}, ${prov}`;
                }

                const total = dataCek.length;
                const avail = dataCek.filter(i => i.status === "AVAILABLE").length;
                const sold = dataCek.filter(i => i.status === "SOLD OUT").length;

                let htmlMessage = `
                    <div style="font-size:1.1rem; line-height: 1.6; padding: 10px 0;">
                        Ada total <span class="swal-highlight text-primary">${total} baliho</span> di wilayah <br> <b>${textLokasi}</b>.
                        <hr style="border-color:#eee; margin:15px 0;">
                        <span class="swal-highlight" style="color:var(--success-green);">${avail}</span> baliho <b style="color:var(--success-green);">Tersedia</b><br>
                        <span class="swal-highlight" style="color:var(--primary-red);">${sold}</span> baliho <b style="color:var(--primary-red);">Tersewa</b>
                    </div>
                `;
                if(total === 0) htmlMessage = `<div class="text-danger fw-bold fs-5 mt-3">Belum ada data baliho di lokasi ini bro.</div>`;

                Swal.fire({ title: 'Statistik Area', html: htmlMessage, icon: total === 0 ? 'info' : 'success', confirmButtonColor: '#121212', confirmButtonText: 'Oke, Tutup' });
            });

            // ==========================================
            // 4. PREVIEW FOTO BASE64
            // ==========================================
            function handleUpload(inputId, previewId, placeholderId, base64Id) {
                $(inputId).change(function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if(file.size > 2 * 1024 * 1024) return alert("Maksimal ukuran foto 2MB bro!");
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            $(base64Id).val(event.target.result);
                            $(previewId).attr('src', event.target.result).show();
                            if(placeholderId) $(placeholderId).hide();
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
            handleUpload('#file-add', '#preview-add', '#placeholder-add', '#add-base64');
            handleUpload('#file-edit', '#preview-edit', null, '#edit-base64');

            // ==========================================
            // 5. AJAX CRUD KE MYSQL LARAVEL
            // ==========================================

            // POST - Tambah Data Baru
            $('#form-tambah').submit(function(e) {
                e.preventDefault();
                const fotoFinal = $('#add-base64').val();
                if(!fotoFinal) return Swal.fire('Error', 'Foto wajib di-upload bro!', 'error');

                const payload = {
                    provinsi: $('#add-provinsi').val(),
                    kabupaten: $('#add-kabupaten').val(),
                    titik: $('#add-titik').val(),
                    foto_kecil: fotoFinal,
                    foto_besar: fotoFinal,
                    status: $('#add-status').val()
                };

                $.post('/api/balihos', payload, function(response) {
                    fetchBalihos(); // Refresh table dari db
                    $('#form-tambah')[0].reset();
                    $('#add-base64').val('');
                    $('#preview-add').hide().attr('src','');
                    $('#placeholder-add').show();
                    $('#add-kabupaten').html('<option value="">Pilih Provinsi Dahulu</option>').prop('disabled', true);
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Data tersimpan', showConfirmButton: false, timer: 1500 });
                });
            });

            // PATCH - Toggle Status
            window.toggleStatus = function(id) {
                $.ajax({
                    url: `/api/balihos/${id}/toggle`,
                    type: 'PATCH',
                    success: function() {
                        fetchBalihos();
                    }
                });
            };

            // DELETE - Hapus Data
            window.hapusData = function(id) {
                Swal.fire({
                    title: 'Yakin hapus bro?', text: "Data bakal hilang dari database permanen lho!", icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/balihos/${id}`,
                            type: 'DELETE',
                            success: function() {
                                fetchBalihos();
                                Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Berhasil dihapus!', showConfirmButton:false, timer:1500 });
                            }
                        });
                    }
                });
            };

            // OPEN MODAL EDIT
            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            window.openEdit = function(id) {
                const item = database.find(i => i.id == id);
                $('#edit-id').val(item.id);
                $('#edit-titik').val(item.titik);
                $('#edit-status').val(item.status);
                $('#edit-base64').val(item.foto_kecil);
                $('#preview-edit').attr('src', item.foto_kecil);

                $('#edit-provinsi').val(item.provinsi);
                const provId = $('#edit-provinsi').find(':selected').data('id');
                if(provId) {
                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
                        .then(r => r.json()).then(regencies => {
                            let options = '<option value="SEMUA">-- Semua Kota/Kab --</option>';
                            regencies.forEach(reg => options += `<option value="${reg.name}">${reg.name}</option>`);
                            $('#edit-kabupaten').html(options).prop('disabled', false).val(item.kabupaten);
                        });
                }
                editModal.show();
            };

            // PUT - Simpan Edit
            $('#form-edit').submit(function(e) {
                e.preventDefault();
                const idEdit = $('#edit-id').val();

                const payload = {
                    provinsi: $('#edit-provinsi').val(),
                    kabupaten: $('#edit-kabupaten').val(),
                    titik: $('#edit-titik').val(),
                    foto_kecil: $('#edit-base64').val(),
                    foto_besar: $('#edit-base64').val(),
                    status: $('#edit-status').val()
                };

                $.ajax({
                    url: `/api/balihos/${idEdit}`,
                    type: 'PUT',
                    data: payload,
                    success: function() {
                        fetchBalihos();
                        editModal.hide();
                        Swal.fire({ toast:true, position:'top-end', icon:'success', title:'data berhasil di update', showConfirmButton:false, timer:1500 });
                    }
                });
            });

        });
    </script>
</body>
</html>
