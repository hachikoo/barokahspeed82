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
@media (max-width:768px){

    .modal-dialog{
        margin: 10px !important;
    }

    .modal-content{
        border-radius:14px !important;
    }

    .modal-body{
        max-height: 65vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    .modal-body{
    padding-bottom:90px !important;
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

// ==========================
// 1. API HELPER
// ==========================
const API = {
    get: async (url) => {
        const res = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return await res.json();
    }
};

// ==========================
// 2. STATE
// ==========================
let state = {
    currentPage: 1,
    isLoading: false,
    hasMoreData: true,
    container: null,
    scrollBox: null
};

// ==========================
// 3. UTILS
// ==========================
const utils = {
    formatRupiah: (val) => {
        if (!val) return "0";
        let clean = val.toString().replace(/\D/g, '');
        return clean.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    showLoading: (show) => {
        const el = document.getElementById('loading-state');
        if (el) el.classList.toggle('d-none', !show);
    }
};

// ==========================
// 4. FETCH SPAREPART
// ==========================
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

        const url = `{{ route('sparepart.get-data') }}?${params.toString()}`;
        const result = await API.get(url);

        renderData(result.data || [], append, result.total || 0);

        state.hasMoreData = !!result.next_page;
        if (state.hasMoreData) state.currentPage++;

    } catch (err) {
        console.error("Fetch Sparepart Error:", err);
    } finally {
        state.isLoading = false;
        utils.showLoading(false);
    }
}

// ==========================
// 5. FETCH LOW STOCK
// ==========================
async function fetchLowStock() {
    try {
        const result = await API.get("/master/sparepart/low-stock");
        console.log("LOW STOCK:", result);
    } catch (err) {
        console.error("Low Stock Error:", err);
    }
}

// ==========================
// 6. RENDER DATA
// ==========================
function renderData(data, append = false, total = 0) {
    if (!state.container) return;

    let html = '';

    if (data.length === 0 && !append) {
        state.container.innerHTML =
            '<tr><td colspan="7" class="text-center p-5 text-muted">Data tidak ditemukan</td></tr>';
        return;
    }

    data.forEach((item, index) => {
        const rowNo = append
            ? state.container.querySelectorAll('tr').length + index + 1
            : index + 1;

        html += `
        <tr>
            <td>${rowNo}</td>
            <td>${item.nama_part}</td>
            <td>Rp ${utils.formatRupiah(item.harga_beli)}</td>
            <td>Rp ${utils.formatRupiah(item.harga_jual)}</td>
            <td>${item.stok}</td>
        </tr>`;
    });

    if (append) {
        state.container.insertAdjacentHTML('beforeend', html);
    } else {
        state.container.innerHTML = html;
    }
}

// ==========================
// 7. DELETE
// ==========================
window.deletePart = async function(id) {
    if (!confirm('Hapus data?')) return;

    try {
        await fetch(`{{ url('sparepart') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        fetchSpareparts(false);

    } catch (err) {
        console.error("Delete error:", err);
    }
};

// ==========================
// 8. INIT (HANYA SATU)
// ==========================
document.addEventListener('DOMContentLoaded', function () {

    state.container = document.getElementById('sparepart-container');
    state.scrollBox = document.getElementById('scroll-container');

    fetchSpareparts();
    fetchLowStock();

    // infinite scroll
    state.scrollBox?.addEventListener('scroll', () => {
        const bottom =
            state.scrollBox.scrollTop + state.scrollBox.clientHeight >=
            state.scrollBox.scrollHeight - 50;

        if (bottom) fetchSpareparts(true);
    });

    // search debounce
    let timer;
    ['search-sparepart', 'filter-kategori', 'sort-stok'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => fetchSpareparts(false), 400);
        });
    });

});

</script>
@endpush
