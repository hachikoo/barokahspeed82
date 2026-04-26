@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        table {
        table-layout: fixed !important;
        width: 100% !important;
    }

thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            padding: 12px 15px !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }
        tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 14px !important;
            padding: 12px 15px !important;
        }
        /* Badge Nomor Seragam */
        .no-badge {
            display: inline-block;
            width: 28px;
            height: 28px;
            line-height: 28px;
            background-color: #f1f5f9;
            color: #64748b;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
        }

        .dataTables_info {
            padding: 20px !important;
            font-size: 13px !important;
            color: #64748b !important;
            font-weight: 500 !important;
        }

        /* Sinkronisasi posisi harga agar tepat di bawah header */
        .kolom-harga {
            text-align: left !important;
            padding-left: 4rem !important;
            vertical-align: middle;
        }

        #sparepart-container tr:hover {
            background-color: #f8f9fa;
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .stok-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 8px;
            border-bottom: 1px solid #f8f9fa;
        }

        .stok-item:last-child {
            border-bottom: none;
        }

        .badge-sisa {
            background-color: #dc3545;
            color: white;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-tambah-stok {
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            transition: all 0.2s;
        }

        .btn-tambah-stok:hover {
            background: #f8f9fa;
            color: #0d6efd;
        }

/* ==================================
   RESPONSIVE MOBILE ONLY
   Desktop tetap original
================================== */
@media (max-width: 768px){

    .container-fluid{
        padding-left:10px !important;
        padding-right:10px !important;
    }

    .card{
        border-radius:14px !important;
        margin-bottom:12px;
    }

    .card-body{
        padding:14px !important;
    }

    h5{
        font-size:18px !important;
    }

    small{
        font-size:12px !important;
    }

    .btn{
        min-height:44px;
        font-size:14px;
        border-radius:10px;
    }

    .btn-sm{
        min-height:42px;
        padding:8px 12px !important;
    }

    .row.g-2 > div,
    .modal .row > div{
        width:100%;
    }

    .form-control,
    .form-select,
    .input-group-text{
        min-height:46px;
        font-size:15px;
    }

    /* ===== TABLE HP SAJA ===== */
    #scroll-container{
        width:100%;
        max-width:100%;
        overflow-x:auto !important;
        overflow-y:auto !important;
        -webkit-overflow-scrolling:touch;
        max-height:none !important;
        display:block;
    }

    #scroll-container table{
        min-width:1100px !important;
        width:max-content !important;
        table-layout:auto !important;
    }

    #scroll-container th,
    #scroll-container td{
        white-space:nowrap !important;
        font-size:13px !important;
        padding:10px !important;
        vertical-align:middle;
    }

    #scroll-container thead th{
        font-size:11px !important;
    }

    .card-footer{
        padding:12px 14px !important;
    }

    /* ===== MODAL HP ===== */
      .modal{
        padding:0 !important;
    }

    .modal-dialog{
        margin:0 !important;
        max-width:100% !important;
        width:100% !important;
        height:100vh !important;
    }

    .modal-content{
        height:100vh !important;
        border-radius:0 !important;
        display:flex;
        flex-direction:column;
    }

    .modal-header{
        flex-shrink:0;
    }

    .modal-body{
        flex:1;
        overflow-y:auto !important;
        padding:16px !important;
        -webkit-overflow-scrolling:touch;
    }

    .modal-footer{
        flex-shrink:0;
        padding:12px 16px !important;
        background:#fff;
        border-top:1px solid #eee;
    }
    body.modal-sidebar-open #sidebarToggle,
body.modal-sidebar-open .sidebar-toggle,
body.modal-sidebar-open .mobile-toggle,
body.modal-sidebar-open .menu-toggle,
body.modal-sidebar-open .navbar-toggler,
body.modal-sidebar-open .btn-toggle,
body.modal-sidebar-open .toggle-sidebar{
    display:none !important;
}
}
    </style>
    <div class="container-fluid px-4">

        {{-- Header & Filter Card --}}
        <div class="card border-0 shadow-sm mb-3">

            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-box-seam me-2 text-primary"></i>Inventory
                            Spareparts</h5>
                        <small class="text-muted">Manajemen stok dan lokasi penyimpanan bengkel</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-danger btn-sm" onclick="openStokMenipis()">
                            <i class="bi bi-exclamation-triangle"></i> Stok Menipis
                        </button>
                        <button type="button" class="btn btn-primary btn-sm px-3" onclick="openAddModal()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Part Baru
                        </button>
                    </div>
                </div>

                <hr class="my-3 opacity-25">

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" id="search-sparepart" class="form-control border-start-0 ps-0"
                                placeholder="Cari kode atau nama barang...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filter-kategori">
                            <option value="">SEMUA KATEGORI</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ strtoupper($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="sort-stok" class="form-select">
                            <option value="">Urutkan Default</option>
                            <option value="low">Stok: Terkecil</option>
                            <option value="high">Stok: Terbanyak</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive" id="scroll-container" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle" style="min-width: 1000px;">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 5%;" class="text-center">No</th>
                                <th style="width: 35%;">Nama Sparepart</th>
                                <th style="width: 15%;">Harga Beli</th>
                                <th style="width: 15%;">Harga Jual</th>
                                <th style="width: 10%;" class="text-center">Rak</th>
                                <th style="width: 10%;" class="text-center">Stok</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th style="width: 10%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sparepart-container">
                        </tbody>
                    </table>
                    <div id="loading-state" class="text-center p-4 d-none">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <div class="small mt-2 text-muted">Memuat data...</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div id="info-pencatatan" class="text-muted small">
                        Menampilkan 0 dari 0 sparepart
                    </div>

                    <div id="loading-state" class="d-none">
                        <span class="spinner-border spinner-border-sm text-primary"></span> Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="modalSparepart" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalSparepartLabel">Tambah Sparepart Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formSparepart">
                    @csrf
                    <input type="hidden" name="id" id="modal_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Kode Part</label>
                                <input type="text" name="kode_part" id="modal_kode_part" class="form-control"
                                    placeholder="BND-001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Kategori</label>
                                <input type="text" name="kategori" id="modal_kategori" class="form-control"
                                    placeholder="BAN / OLI" required style="text-transform: uppercase;">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Nama Sparepart</label>
                                <input type="text" name="nama_part" id="modal_nama_part" class="form-control"
                                    placeholder="NAMA BARANG LENGKAP" required style="text-transform: uppercase;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="harga_beli" id="modal_harga_beli"
                                        class="form-control format-rupiah" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="harga_jual" id="modal_harga_jual"
                                        class="form-control format-rupiah" required>
                                </div>
                            </div>
                         <div class="col-md-6">
        <label class="form-label fw-bold small">Stok Saat Ini</label>
        <input type="number" name="stok" id="modal_stok" class="form-control" required min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold small text-danger">Batas Stok Minimum</label>
        <input type="number" name="stok_min" id="modal_stok_min" class="form-control" required min="0" placeholder="0">
        <div class="form-text" style="font-size: 11px;">Sistem akan memberi peringatan jika stok mencapai angka ini.</div>
    </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Lokasi Rak (Opsional)</label>
                                <input type="text" name="rak" id="modal_rak" class="form-control"
                                    placeholder="RAK-A1" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('master.sparepart.modal')
@endsection

@push('scripts')
    <script>

          document.addEventListener('DOMContentLoaded', function () {

    const toggleBtn =
        document.querySelector('#sidebarToggle') ||
        document.querySelector('.sidebar-toggle') ||
        document.querySelector('[data-bs-target="#sidebar"]') ||
        document.querySelector('.btn-toggle');

    if (!toggleBtn) return;

    const modalIds = [
        'modalSparepart',
        'modalMutasi',
        'modalStokMenipis',
        'modalTambahStokSimple'
    ];

    modalIds.forEach(id => {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.addEventListener('show.bs.modal', function () {
            toggleBtn.style.display = 'none';
        });

        modal.addEventListener('hidden.bs.modal', function () {
            toggleBtn.style.display = '';
        });
    });

});
        // 1. STATE & GLOBAL VARIABLES
        let state = {
            currentPage: 1,
            isLoading: false,
            hasMoreData: true,
            container: document.getElementById('sparepart-container'),
            scrollBox: document.getElementById('scroll-container')
        };

        // 2. UTILS
        const utils = {
            formatRupiah: (val) => {
                if (!val) return "0";
                let clean = val.toString().replace(/\D/g, '');
                return clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            cleanRupiah: (val) => val.toString().replace(/\./g, ''),
            showLoading: (show) => {
                const el = document.getElementById('loading-state');
                if (el) show ? el.classList.remove('d-none') : el.classList.add('d-none');
            }
        };

        // 3. GLOBAL FUNCTIONS (Agar bisa dipanggil dari HTML onclick)

        // Fungsi Tambah Baru
        window.openAddModal = function() {
            const form = document.getElementById('formSparepart');
            if (form) {
                form.reset();
                document.getElementById('modal_id').value = '';
                document.getElementById('modal_stok_min').value = '0'; // Default 0
                document.getElementById('modalSparepartLabel').innerText = 'TAMBAH SPAREPART BARU';
                document.getElementById('modal_kode_part').readOnly = true;

                // Generate kode otomatis
                generateAutoCode();

                const modalEl = document.getElementById('modalSparepart');
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        };

        async function generateAutoCode() {
            try {
                const res = await fetch("{{ route('sparepart.generate-code') }}?t=" + Date.now());
                const data = await res.json();
                const inputKode = document.getElementById('modal_kode_part');
                if (inputKode) inputKode.value = data.new_code || data.kode;
            } catch (e) {
                console.error("Gagal generate kode:", e);
            }
        }

        // Fungsi Mutasi
        window.openMutasiModal = function(btn) {
            try {
                const item = JSON.parse(decodeURIComponent(btn.getAttribute('data-item')));
                const modalEl = document.getElementById('modalMutasi');
                if (modalEl) {
                    document.getElementById('mutasi_sparepart_id').value = item.id;
                    document.getElementById('label_nama_part').innerText = item.nama_part.toUpperCase();
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            } catch (e) {
                console.error("Error modal mutasi:", e);
            }
        };

        // Fungsi Edit
       window.prepEdit = function(btn) {
    try {
        const item = JSON.parse(decodeURIComponent(btn.getAttribute('data-item')));
        document.getElementById('modal_id').value = item.id;
        document.getElementById('modal_kode_part').value = item.kode_part;
        document.getElementById('modal_nama_part').value = item.nama_part;
        document.getElementById('modal_kategori').value = item.kategori;
        document.getElementById('modal_rak').value = item.rak || '';
        document.getElementById('modal_stok').value = item.stok;

        // TAMBAHKAN INI
        document.getElementById('modal_stok_min').value = item.stok_min || 0;

        document.getElementById('modal_status').value = item.status;
        document.getElementById('modal_harga_beli').value = utils.formatRupiah(item.harga_beli);
        document.getElementById('modal_harga_jual').value = utils.formatRupiah(item.harga_jual);

        document.getElementById('modalSparepartLabel').innerText = 'EDIT DATA SPAREPART';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalSparepart')).show();
    } catch (e) {
        console.error("Error prep edit:", e);
    }
};

        // Fungsi Hapus
        window.deletePart = async function(id) {
            const confirm = await Swal.fire({
                title: 'Hapus data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus'
            });
            if (confirm.isConfirmed) {
                const res = await fetch(`{{ url('sparepart') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    Swal.fire('Terhapus!', '', 'success');
                    fetchSpareparts(false);
                }
            }
        };


        window.toggleStatus = async function(id, element) {
    const originalState = element.checked;

    try {
        const response = await fetch(`{{ url('sparepart/toggle-status') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.status === 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: result.message
            });
        } else {
            element.checked = !originalState; // Kembalikan posisi switch jika gagal
            Swal.fire('Gagal', 'Gagal merubah status', 'error');
        }
    } catch (err) {
        element.checked = !originalState;
        Swal.fire('Error', 'Kesalahan koneksi server', 'error');
    }
};

        // 4. CORE FETCH DATA
        async function fetchSpareparts(append = false) {
            if (state.isLoading || (append && !state.hasMoreData)) return;
            if (!append) {
                state.currentPage = 1;
                state.hasMoreData = true;
            }

            state.isLoading = true;
            utils.showLoading(true);

            try {
                const params = new URLSearchParams({
                    page: state.currentPage,
                    search: document.getElementById('search-sparepart')?.value || '',
                    kategori: document.getElementById('filter-kategori')?.value || '',
                    sort_stok: document.getElementById('sort-stok')?.value || ''
                });

                const response = await fetch(`{{ route('sparepart.get-data') }}?${params.toString()}`);
                const result = await response.json();

                // Oper result.total (misal: 34) ke renderData
                renderData(result.data || [], append, result.total || 0);

                state.hasMoreData = !!result.next_page;
                if (state.hasMoreData) state.currentPage++;

            } catch (error) {
                console.error("Fetch Error:", error);
            } finally {
                state.isLoading = false;
                utils.showLoading(false);
            }
        }

        // Tambahkan parameter totalFromServer di sini
        function renderData(data, append = false, totalFromServer = 0) {
            let html = '';

            if (data.length === 0 && !append) {
                state.container.innerHTML =
                    '<tr><td colspan="7" class="text-center p-5 text-muted">Data tidak ditemukan</td></tr>';

                // Update teks jika kosong
                const displayElement = document.getElementById('info-pencatatan');
                if (displayElement) displayElement.innerHTML =
                    `Menampilkan <strong>0</strong> dari <strong>0</strong> sparepart`;
                return;
            }

            data.forEach((item, index) => {
                const rowNo = append ? state.container.querySelectorAll('tr').length + index + 1 : index + 1;
                const itemJson = encodeURIComponent(JSON.stringify(item));
                const isLowStock = parseInt(item.stok) <= parseInt(item.stok_min);
                const badgeClass = isLowStock ? 'bg-danger text-white' : 'bg-light text-dark border';
                html += `
            <tr>
                <td class="text-center text-muted small">${rowNo}</td>
                <td>
                    <div class="fw-bold text-dark">${item.nama_part}</div>
                    <div class="small text-muted">${item.kode_part} | <span class="text-primary">${(item.kategori || '').toUpperCase()}</span></div>
                </td>
                <td>Rp ${utils.formatRupiah(item.harga_beli)}</td>
                <td class="text-primary fw-bold">Rp ${utils.formatRupiah(item.harga_jual)}</td>
                <td class="text-center">${item.rak || '-'}</td>
                <td class="text-center">
                    <span class="badge ${badgeClass}" style="min-width: 30px;">${item.stok}</span>
                    ${isLowStock ? `<div class="text-danger fw-bold" style="font-size: 9px; margin-top: 2px;">LIMIT: ${item.stok_min}</div>` : ''}
                </td>
                <td class="text-center">
                    <div class="form-check form-switch d-flex justify-content-center">
                        <input class="form-check-input status-switch" type="checkbox" ${item.status == 1 ? 'checked' : ''} onclick="toggleStatus(${item.id}, this)">
                    </div>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm text-info" onclick="openMutasiModal(this)" data-item="${itemJson}"><i class="bi bi-box-seam"></i></button>
                    <button class="btn btn-sm text-warning" onclick="prepEdit(this)" data-item="${itemJson}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm text-danger" onclick="deletePart(${item.id})"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`;
            });

            if (append) {
                state.container.insertAdjacentHTML('beforeend', html);
            } else {
                state.container.innerHTML = html;
            }

            // Hitung baris yang ada di tabel
            const totalDiHalaman = state.container.querySelectorAll('tr').length;

            // Tembak langsung menggunakan total yang dikirim dari fetch tadi
            const displayElement = document.getElementById('info-pencatatan');
            if (displayElement) {
                displayElement.innerHTML =
                    `Menampilkan <strong>${totalDiHalaman}</strong> dari <strong>${totalFromServer}</strong> sparepart`;
            }
        }

        // Fungsi pembantu untuk update teks di bawah tabel
        function updateItemCount(current, total) {
            const displayElement = document.querySelector('.card-footer .text-muted') ||
                document.querySelector('div[class*="Menampilkan"]');

            if (displayElement) {
                displayElement.innerHTML =
                    `Menampilkan <strong>${current}</strong> dari <strong>${total}</strong> sparepart`;
            }
        }

        // 5. INITIALIZE & EVENTS
        document.addEventListener('DOMContentLoaded', function() {
            fetchSpareparts();



            // Submit Form Sparepart (Tambah/Edit)
            document.getElementById('formSparepart')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;

                try {
                    const response = await fetch("{{ route('sparepart.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    });
                    const result = await response.json();
                    if (response.ok && result.status === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('modalSparepart')).hide();
                        Swal.fire('Berhasil', result.message, 'success');
                        fetchSpareparts(false);
                    } else {
                        Swal.fire('Gagal', result.message, 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', 'Kesalahan Sistem', 'error');
                } finally {
                    btn.disabled = false;
                }
            });

            // Submit Form Mutasi
            document.getElementById('formMutasi')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = this.querySelector('button[type="submit"]');
                btn.disabled = true;
                try {
                    const response = await fetch("{{ route('sparepart.mutasi') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: new FormData(this)
                    });
                    const result = await response.json();
                    if (response.ok && result.status === 'success') {
                        bootstrap.Modal.getInstance(document.getElementById('modalMutasi')).hide();
                        this.reset();
                        Swal.fire('Berhasil!', result.message, 'success');
                        fetchSpareparts(false);
                    } else {
                        Swal.fire('Gagal', result.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Server Error', 'error');
                } finally {
                    btn.disabled = false;
                }
            });

            // Infinite Scroll
            state.scrollBox?.addEventListener('scroll', () => {
                const isBottom = state.scrollBox.scrollTop + state.scrollBox.clientHeight >= state.scrollBox
                    .scrollHeight - 50;
                if (isBottom && !state.isLoading && state.hasMoreData) fetchSpareparts(true);
            });

            // Search & Filter dengan Debounce
            let timer;
            ['search-sparepart', 'filter-kategori', 'sort-stok'].forEach(id => {
                document.getElementById(id)?.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fetchSpareparts(false), 500);
                });
            });
        });

        // Formatting Rupiah & Uppercase (Global)
        document.addEventListener('input', (e) => {
            if (e.target.classList.contains('format-rupiah')) e.target.value = utils.formatRupiah(e.target.value);
            if (['modal_nama_part', 'modal_kategori', 'modal_rak'].includes(e.target.id)) e.target.value = e.target
                .value.toUpperCase();
        });

        // 1. Fungsi buka list stok menipis
        window.openStokMenipis = async function() {
            const listContainer = document.getElementById('list-stok-menipis');
            listContainer.innerHTML =
                '<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-danger"></span></div>';

            // Panggil modal list
            const modalStok = new bootstrap.Modal(document.getElementById('modalStokMenipis'));
            modalStok.show();

            try {
                const response = await fetch("{{ route('sparepart.get-low-stock') }}");
                const data = await response.json();

                let html = '';
                data.forEach(item => {
                    const itemJson = encodeURIComponent(JSON.stringify(item));
                    html += `
                <div class="stok-item d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div class="fw-bold small text-uppercase" style="max-width: 180px;">${item.nama_part}</div>
                    <div class="d-flex align-items-center gap-4">
                        <span class="badge bg-danger rounded-circle d-flex align-items-center justify-content-center"
                              style="width: 25px; height: 25px; font-size: 11px;">${item.stok}</span>
                        <button class="btn btn-outline-secondary rounded-circle p-0"
                                style="width: 30px; height: 30px;"
                                onclick="openQuickAdd('${itemJson}')">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>`;
                });
                listContainer.innerHTML = html || '<div class="text-center p-4">Stok aman semua!</div>';
            } catch (e) {
                listContainer.innerHTML = '<div class="text-center p-3 text-danger">Error loading data.</div>';
            }
        };

        // 2. Fungsi pindah ke modal tambah stok (Popup kecil)
        window.openQuickAdd = function(itemJson) {
            const item = JSON.parse(decodeURIComponent(itemJson));

            // Tutup modal list dulu
            const modalStokEl = document.getElementById('modalStokMenipis');
            const modalStok = bootstrap.Modal.getInstance(modalStokEl);
            if (modalStok) modalStok.hide();

            // Isi data ke modal popup kecil
            document.getElementById('simple-part-name').innerText = item.nama_part;
            document.getElementById('simple-part-id').value = item.id;
            document.getElementById('simple-jumlah').value = 1; // Default kasih 1

            // Tunggu animasi tutup selesai (0.4 detik), baru buka modal baru
            setTimeout(() => {
                const modalAdd = new bootstrap.Modal(document.getElementById('modalTambahStokSimple'));
                modalAdd.show();
            }, 400);
        };

        // 3. Handle pengiriman data (Submit)
        document.getElementById('formTambahStokSimple').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('simple-part-id').value;
            const jumlah = document.getElementById('simple-jumlah').value;

            try {
                const response = await fetch("{{ route('sparepart.tambah-stok') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id,
                        jumlah
                    })
                });

                const res = await response.json();
                if (res.status === 'success') {
                    // Tutup modal popup kecil
                    bootstrap.Modal.getInstance(document.getElementById('modalTambahStokSimple')).hide();

                    // Tampilkan SweetAlert dengan info detail seperti mutasi
                    Swal.fire({
                        icon: 'success',
                        title: 'Stok Diperbarui',
                        html: `Berhasil menambah <b>${res.jumlah_ditambah}</b> item untuk <b>${res.nama}</b>.<br>Total stok sekarang: <b>${res.stok_sekarang}</b>`,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6'
                    });

                    fetchSpareparts(); // Refresh tabel utama agar angka stok berubah
                }
            } catch (err) {
                Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui stok', 'error');
            }
        });


        // DOM TOGGLE MOBILE RESPONSIF

document.addEventListener('DOMContentLoaded', function () {

    function hideSidebarToggle() {
        document.body.classList.add('modal-sidebar-open');

        document.querySelectorAll(`
            #sidebarToggle,
            .sidebar-toggle,
            .mobile-toggle,
            .menu-toggle,
            .navbar-toggler,
            .btn-toggle,
            .toggle-sidebar,
            .hamburger,
            .bi-list
        `).forEach(el => {
            if (el.closest('button')) {
                el.closest('button').style.display = 'none';
            } else {
                el.style.display = 'none';
            }
        });
    }

    function showSidebarToggle() {
        document.body.classList.remove('modal-sidebar-open');

        document.querySelectorAll(`
            #sidebarToggle,
            .sidebar-toggle,
            .mobile-toggle,
            .menu-toggle,
            .navbar-toggler,
            .btn-toggle,
            .toggle-sidebar,
            .hamburger,
            .bi-list
        `).forEach(el => {
            if (el.closest('button')) {
                el.closest('button').style.display = '';
            } else {
                el.style.display = '';
            }
        });
    }

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('show.bs.modal', hideSidebarToggle);
        modal.addEventListener('hidden.bs.modal', showSidebarToggle);
    });

});


    </script>
@endpush
