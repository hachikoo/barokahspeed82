@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Table & Scroll Style */
    #scroll-container {
        height: calc(100vh - 350px);
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 6px;
    }

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
        position: sticky;
        top: 0;
        z-index: 10;
    }

    tbody td {
        border-bottom: 1px solid #f1f5f9 !important;
        font-size: 14px !important;
        padding: 12px 15px !important;
    }



    /* Tombol Aksi */
    .btn-action-custom {
        padding: 5px 8px;
        transition: all 0.2s;
        border: none;
        background: transparent;
    }
    .btn-action-custom:hover {
        background: #f8f9fa;
        transform: scale(1.1);
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

    /* form jadi full */
    .row.g-2 > div{
        width:100%;
    }

    .form-control,
    .form-select,
    .input-group-text{
        min-height:46px;
        font-size:15px;
    }

    /* ===== TABLE KHUSUS HP ===== */
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
        min-width:900px !important; /* penting biar scroll */
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

    /* tombol aksi biar ga kekecilan */
    .btn-action-custom{
        padding:6px 8px;
    }

    /* ===== MODAL FULLSCREEN HP ===== */
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

    .modal-body{
        flex:1;
        overflow-y:auto !important;
        -webkit-overflow-scrolling:touch;
    }

    .modal-footer{
        border-top:1px solid #eee;
    }

}
@media (max-width:768px){
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
/* ==================================
   APP-LIKE MODE (Mobile Only)
================================== */
@media (max-width:768px){

    html, body{
        height:100%;
        overflow:hidden; /* penting biar feel app */
        background:#f1f5f9;
    }

    .container-fluid{
        height:100vh;
        display:flex;
        flex-direction:column;
        padding:10px !important;
        gap:10px;
    }

    /* CARD jadi seperti layar */
    .card{
        border-radius:16px !important;
        overflow:hidden;
        display:flex;
        flex-direction:column;
    }

    /* HEADER FIXED */
    .card:first-child{
        flex-shrink:0;
    }

    /* TABLE AREA SCROLL */
    .card:last-child{
        flex:1;
        min-height:0;
    }

    #scroll-container{
        height:100%;
        overflow:auto !important;
        -webkit-overflow-scrolling:touch;
    }

    /* SCROLL SMOOTH */
    *{
        -webkit-tap-highlight-color: transparent;
    }

    /* BUTTON lebih “mobile” */
    .btn{
        border-radius:12px;
        min-height:44px;
        font-weight:600;
    }

    /* HEADER STYLE */
    h5{
        font-size:17px !important;
    }

    small{
        font-size:12px !important;
    }
}
@media (max-width:768px){

    #scroll-container table{
        min-width:850px;
        width:max-content;
    }

    thead th{
        position:sticky;
        top:0;
        z-index:5;
        background:#fff !important;
    }

}
@media (max-width:768px){

    .modal{
        padding:0 !important;
    }

    .modal-dialog{
        margin:0 !important;
        max-width:100% !important;
        width:100%;
        height:100vh;
    }

    .modal-content{
        height:100vh;
        border-radius:0 !important;
        display:flex;
        flex-direction:column;
        animation: slideUp 0.25s ease;
    }

    @keyframes slideUp{
        from{
            transform:translateY(100%);
        }
        to{
            transform:translateY(0);
        }
    }

    .modal-body{
        flex:1;
        overflow:auto;
    }

}
@media (max-width:768px){
    .card-body{
        padding-bottom:20px !important;
    }

    .modal-body{
        padding-bottom:30px !important;
    }
}
</style>

<div class="container-fluid px-4">
    {{-- Header & Filter Card --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-person-gear me-2 text-primary"></i>Master Data <span class="text-primary">Mekanik</span>
                    </h5>
                    <small class="text-muted">Manajemen kru teknisi dan status ketersediaan operasional</small>
                </div>
                <button class="btn btn-primary btn-sm px-3 fw-bold" onclick="openAddModal()">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Mekanik
                </button>
            </div>

            <hr class="my-3 opacity-25">

            <div class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="search-mekanik" class="form-control border-start-0 ps-0"
                               placeholder="Cari nama, whatsapp, atau alamat...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select fw-bold text-secondary" id="filter-status">
                        <option value="">SEMUA STATUS</option>
                        <option value="aktif">AKTIF</option>
                        <option value="tidak aktif">TIDAK AKTIF</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="scroll-container">
                <table class="table table-hover align-middle mb-0" id="tabel-mekanik">
                    <thead>
                        <tr>
                            <th class="text-center" width="50px">NO</th>
                            <th width="25%">NAMA MEKANIK</th>
                            <th width="15%">NO WHATSAPP</th>
                            <th width="30%">ALAMAT</th>
                            <th class="text-center" width="15%">STATUS</th>
                            <th class="text-center" width="10%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="mekanik-container">
                        {{-- Data diisi via AJAX --}}
                    </tbody>
                </table>

                <div id="loading-state" class="text-center p-4 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 small text-muted">Memuat data...</span>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <div class="small text-muted" id="info-pencatatan">Menampilkan 0 mekanik</div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FORM --}}
<div class="modal fade" id="modalMekanik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark text-uppercase" id="modalMekanikLabel">TAMBAH DATA MEKANIK</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMekanik">
                @csrf
                <input type="hidden" name="id" id="modal_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2 text-uppercase">Nama Lengkap</label>
                        <input type="text" name="nama_mekanik" id="modal_nama" class="form-control text-uppercase" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2 text-uppercase">No. WhatsApp</label>
                        <input type="number" name="whatsapp" id="modal_wa" class="form-control" placeholder="628..." required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-2 text-uppercase">Status</label>
                        <select name="status" id="modal_status" class="form-select">
                            <option value="aktif">AKTIF</option>
                            <option value="tidak aktif">TIDAK AKTIF</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="small fw-bold text-muted mb-2 text-uppercase">Alamat</label>
                        <textarea name="alamat" id="modal_alamat" class="form-control text-uppercase" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let state = {
        currentPage: 1,
        isLoading: false,
        hasMoreData: true,
        container: document.getElementById('mekanik-container'),
        scrollBox: document.getElementById('scroll-container')
    };

    /** FETCH DATA **/
    async function fetchMekanik(append = false) {
        if (state.isLoading || (append && !state.hasMoreData)) return;
        if (!append) { state.currentPage = 1; state.hasMoreData = true; }

        state.isLoading = true;
        document.getElementById('loading-state').classList.remove('d-none');

        try {
            const params = new URLSearchParams({
                page: state.currentPage,
                search: document.getElementById('search-mekanik').value,
                status: document.getElementById('filter-status').value
            });

            const res = await fetch(`{{ route('mekanik.get-data') }}?${params}`, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await res.json();

            renderData(result.data || [], append, result.total || 0);
            state.hasMoreData = !!result.next_page_url;
            if (state.hasMoreData) state.currentPage++;

        } catch (e) { console.error(e); }
        finally {
            state.isLoading = false;
            document.getElementById('loading-state').classList.add('d-none');
        }
    }

    /** RENDER DATA **/
    function renderData(data, append, total) {
        let html = '';
        if (data.length === 0 && !append) {
            state.container.innerHTML = '<tr><td colspan="6" class="text-center p-5 text-muted">Data mekanik tidak ditemukan</td></tr>';
            return;
        }

        data.forEach((item, index) => {
            const rowNo = append ? state.container.querySelectorAll('tr').length + index + 1 : index + 1;
            const itemJson = encodeURIComponent(JSON.stringify(item));
            const isAktif = item.status === 'aktif';

            html += `
                <tr>
                    <td class="text-center"><span class="no-badge">${rowNo}</span></td>
                    <td><div class="fw-bold text-dark text-uppercase">${item.nama_mekanik}</div></td>
                    <td>
                        <a href="https://wa.me/${item.whatsapp}" target="_blank" class="text-success small fw-bold text-decoration-none">
                            <i class="bi bi-whatsapp"></i> ${item.whatsapp}
                        </a>
                    </td>
                    <td><span class="text-muted small text-uppercase">${item.alamat || '-'}</span></td>
                    <td class="text-center">
                        <span class="badge rounded-pill ${isAktif ? 'bg-success' : 'bg-danger'}" style="font-size: 0.65rem; padding: 5px 12px;">
                            ${item.status.toUpperCase()}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-action-custom text-warning" onclick="prepEdit('${itemJson}')">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>
                            <button class="btn btn-action-custom text-danger" onclick="deleteMekanik(${item.id})">
                                <i class="bi bi-trash3 fs-5"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
        });

        if (append) state.container.insertAdjacentHTML('beforeend', html);
        else state.container.innerHTML = html;

        document.getElementById('info-pencatatan').innerHTML = `Menampilkan <strong>${state.container.querySelectorAll('tr').length}</strong> dari <strong>${total}</strong> mekanik`;
    }

    /** MODAL HANDLERS **/
    window.openAddModal = function() {
        document.getElementById('formMekanik').reset();
        document.getElementById('modal_id').value = '';
        document.getElementById('modalMekanikLabel').innerText = 'TAMBAH DATA MEKANIK';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalMekanik')).show();
    };

    window.prepEdit = function(json) {
        const item = JSON.parse(decodeURIComponent(json));
        document.getElementById('modal_id').value = item.id;
        document.getElementById('modal_nama').value = item.nama_mekanik;
        document.getElementById('modal_wa').value = item.whatsapp;
        document.getElementById('modal_alamat').value = item.alamat || '';
        document.getElementById('modal_status').value = item.status;

        document.getElementById('modalMekanikLabel').innerText = 'EDIT DATA MEKANIK';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalMekanik')).show();
    };

    /** DELETE HANDLER **/
    window.deleteMekanik = function(id) {
        Swal.fire({
            title: 'Hapus Mekanik?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('mekanik/delete') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Berhasil', data.message, 'success');
                    fetchMekanik(false);
                });
            }
        });
    };

    /** INIT & EVENTS **/
    document.addEventListener('DOMContentLoaded', () => {
        fetchMekanik();

        // Infinite Scroll logic
        state.scrollBox.addEventListener('scroll', () => {
            if (state.scrollBox.scrollTop + state.scrollBox.clientHeight >= state.scrollBox.scrollHeight - 20) {
                fetchMekanik(true);
            }
        });

        // Search & Filter Event
        ['search-mekanik', 'filter-status'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                clearTimeout(this.timer);
                this.timer = setTimeout(() => fetchMekanik(false), 500);
            });
        });

        // Submit Form
        document.getElementById('formMekanik').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('modal_id').value;
            let url = id ? `{{ url('mekanik/update') }}/${id}` : "{{ route('mekanik.store') }}";

            const formData = new FormData(this);
            if(id) formData.append('_method', 'PUT');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (res.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('modalMekanik')).hide();
                    Swal.fire('Berhasil', 'Data berhasil disimpan', 'success');
                    fetchMekanik(false);
                }
            } catch (err) { console.error(err); }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

    function hideToggle() {
        document.body.classList.add('modal-sidebar-open');

        document.querySelectorAll(`
            #sidebarToggle,
            .sidebar-toggle,
            .mobile-toggle,
            .menu-toggle,
            .navbar-toggler,
            .btn-toggle,
            .toggle-sidebar
        `).forEach(el => {
            if (el.closest('button')) {
                el.closest('button').style.display = 'none';
            } else {
                el.style.display = 'none';
            }
        });
    }

    function showToggle() {
        document.body.classList.remove('modal-sidebar-open');

        document.querySelectorAll(`
            #sidebarToggle,
            .sidebar-toggle,
            .mobile-toggle,
            .menu-toggle,
            .navbar-toggler,
            .btn-toggle,
            .toggle-sidebar
        `).forEach(el => {
            if (el.closest('button')) {
                el.closest('button').style.display = '';
            } else {
                el.style.display = '';
            }
        });
    }

    // AUTO DETECT SEMUA MODAL
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('show.bs.modal', hideToggle);
        modal.addEventListener('hidden.bs.modal', showToggle);
    });

});
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.modal').forEach(modal => {

        modal.addEventListener('show.bs.modal', () => {
            document.body.style.overflow = 'hidden';
        });

        modal.addEventListener('hidden.bs.modal', () => {
            document.body.style.overflow = '';
        });

    });

});

</script>
@endpush
