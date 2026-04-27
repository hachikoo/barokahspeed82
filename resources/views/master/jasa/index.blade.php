@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Konsistensi dengan Master Konsumen */
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

    .badge-biaya {
        background-color: #f8faff;
        color: #0d6efd;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 700;
        border: 1px solid #e0e9ff;
        display: inline-block;
        min-width: 110px;
    }

    /* ==================================
   RESPONSIVE MOBILE - MASTER JASA
   Samakan rules dengan laman sebelumnya
================================== */
@media (max-width: 768px){

    .container-fluid{
        padding-left:10px !important;
        padding-right:10px !important;
    }

    /* card */
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

    /* tombol */
    .btn{
        min-height:44px;
        font-size:14px;
        border-radius:10px;
    }

    .btn-sm{
        min-height:42px;
        padding:8px 12px !important;
    }

    /* search full width */
    .row.g-2 > div{
        width:100%;
    }

    .form-control,
    .form-select,
    .input-group-text{
        min-height:46px;
        font-size:15px;
    }

    /* scroll tabel */
    #scroll-container{
        height:auto !important;
        max-height:none !important;
        overflow-x:auto !important;
        overflow-y:auto !important;
        -webkit-overflow-scrolling:touch;
        border-radius:12px;
        width:100%;
        max-width:100%;
    }

    #scroll-container table{
        min-width:700px !important;
        width:max-content !important;
        table-layout:auto !important;
    }

    #scroll-container th,
    #scroll-container td{
        white-space:nowrap;
        vertical-align:middle;
    }

    thead th{
        font-size:11px !important;
        padding:10px !important;
    }

    tbody td{
        font-size:13px !important;
        padding:10px !important;
    }

    /* badge biaya */
    .badge-biaya{
        min-width:auto !important;
        font-size:12px !important;
        padding:6px 10px !important;
    }

    /* footer */
    .card-footer{
        padding:12px 14px !important;
    }

    /* modal full mobile */
    .modal-dialog{
        margin:0 !important;
        max-width:100% !important;
        height:100%;
    }

    .modal-content{
        min-height:80vh;
        border-radius:0 !important;
    }

    .modal-body{
        padding:16px !important;
    }

    .modal-footer{
        padding:12px 16px !important;
    }

    /* tombol aksi tabel */
    td .btn{
        min-height:auto;
        padding:6px 8px !important;
    }
}
</style>

<div class="container-fluid px-4">
    {{-- Header Card --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-tools me-2 text-primary"></i>Master Data Jasa</h5>
                    <small class="text-muted">Manajemen daftar layanan dan biaya estimasi bengkel</small>
                </div>
                <button class="btn btn-primary btn-sm px-3" onclick="openAddModal()">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jasa
                </button>
            </div>
            <hr class="my-3 opacity-25">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="search-jasa" class="form-control border-start-0 ps-0" placeholder="Cari nama jasa...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="scroll-container">
                <table class="table table-hover align-middle mb-0" id="tabel-jasa">
                    <thead>
                        <tr>
                            <th class="text-center" width="8%">No</th>
                            <th width="50%">Layanan Jasa</th>
                            <th width="27%">Biaya Estimasi</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jasa-container"></tbody>
                </table>
                <div id="loading-state" class="text-center p-4 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2 small text-muted">Memuat data...</span>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            <div class="small text-muted" id="info-pencatatan">Menampilkan 0 jasa</div>
        </div>
    </div>
</div>

{{-- Modal Add/Edit --}}
<div class="modal fade" id="modalJasa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark text-uppercase" id="modalJasaLabel">INPUT DATA JASA</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formJasa">
                @csrf
                <input type="hidden" name="id" id="modal_id">
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-2 text-uppercase">Nama Layanan Jasa</label>
                        <input type="text" name="nama_jasa" id="modal_nama" class="form-control text-uppercase" required>
                    </div>
                    <div class="mb-0">
                        <label class="small fw-bold text-muted mb-2 text-uppercase">Biaya Jasa (Rp)</label>
                        <input type="number" name="biaya_jasa" id="modal_biaya" class="form-control" placeholder="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm" id="btnSimpan">SIMPAN DATA</button>
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
        container: document.getElementById('jasa-container'),
        scrollBox: document.getElementById('scroll-container')
    };

    /** FETCH DATA **/
    async function fetchJasa(append = false) {
        if (state.isLoading || (append && !state.hasMoreData)) return;
        if (!append) { state.currentPage = 1; state.hasMoreData = true; }

        state.isLoading = true;
        document.getElementById('loading-state').classList.remove('d-none');

        try {
            const params = new URLSearchParams({
                page: state.currentPage,
                search: document.getElementById('search-jasa').value
            });

            const res = await fetch(`{{ route('jasa.get-data') }}?${params}`);
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
            state.container.innerHTML = '<tr><td colspan="4" class="text-center p-5 text-muted">Data jasa tidak ditemukan</td></tr>';
            return;
        }

        data.forEach((item, index) => {
            const rowNo = append ? state.container.querySelectorAll('tr').length + index + 1 : index + 1;
            const itemJson = encodeURIComponent(JSON.stringify(item));

            html += `
                <tr>
                    <td class="text-center text-muted small">${rowNo}</td>
                    <td>
                        <div class="fw-bold text-dark text-uppercase">${item.nama_jasa}</div>
                        <small class="text-muted" style="font-size:0.7rem">JS-${String(item.id).padStart(3, '0')}</small>
                    </td>
                    <td><span class="badge-biaya">Rp ${Number(item.biaya_jasa).toLocaleString('id-ID')}</span></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-action-custom text-warning" onclick="prepEdit('${itemJson}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-action-custom text-danger" onclick="deleteJasa(${item.id})">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
        });

        if (append) state.container.insertAdjacentHTML('beforeend', html);
        else state.container.innerHTML = html;

        document.getElementById('info-pencatatan').innerHTML = `Menampilkan <strong>${state.container.querySelectorAll('tr').length}</strong> dari <strong>${total}</strong> jasa`;
    }

    /** MODAL HANDLERS (Sudah Termasuk Reset & Fix Null) **/
    window.openAddModal = function() {
        const form = document.getElementById('formJasa');
        if (form) form.reset();

        document.getElementById('modal_id').value = '';
        document.getElementById('modalJasaLabel').innerText = 'INPUT DATA JASA';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalJasa')).show();
    };

    window.prepEdit = function(json) {
        const item = JSON.parse(decodeURIComponent(json));
        document.getElementById('modal_id').value = item.id;
        document.getElementById('modal_nama').value = item.nama_jasa;
        document.getElementById('modal_biaya').value = Math.floor(item.biaya_jasa);
        document.getElementById('modalJasaLabel').innerText = 'EDIT DATA JASA';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalJasa')).show();
    };

    /** SUBMIT FORM (AJAX) **/
    document.getElementById('formJasa').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('modal_id').value;
        const btnSimpan = document.getElementById('btnSimpan');

        let url = id ? `{{ url('master/jasa/update') }}/${id}` : "{{ route('jasa.store') }}";
        const formData = new FormData(this);
        if (id) formData.append('_method', 'PUT');

        btnSimpan.disabled = true;

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' },
                body: formData
            });

            const result = await res.json();
            if (res.ok) {
                bootstrap.Modal.getInstance(document.getElementById('modalJasa')).hide();
                Swal.fire('Berhasil', result.message, 'success');
                this.reset();
                fetchJasa(false);
            } else {
                Swal.fire('Error', result.message || "Gagal menyimpan", 'error');
            }
        } catch (err) {
            Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error');
        } finally {
            btnSimpan.disabled = false;
        }
    });

    /** DELETE LOGIC **/
    window.deleteJasa = function(id) {
        Swal.fire({
            title: 'Hapus Jasa?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('master/jasa/delete') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Terhapus', data.message, 'success');
                    fetchJasa(false);
                });
            }
        });
    }

    /** INIT **/
    document.addEventListener('DOMContentLoaded', () => {
        fetchJasa();
        state.scrollBox.addEventListener('scroll', () => {
            if (state.scrollBox.scrollTop + state.scrollBox.clientHeight >= state.scrollBox.scrollHeight - 10) {
                fetchJasa(true);
            }
        });

        let timer;
        document.getElementById('search-jasa').addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => fetchJasa(false), 500);
        });
    });

    document.addEventListener('shown.bs.modal', function () {
    const toggleBtn = document.querySelector('.toggle-sidebar, #toggleSidebar, [onclick*="toggleSidebar"]');
    if (toggleBtn) toggleBtn.style.display = 'none';
});

document.addEventListener('hidden.bs.modal', function () {
    const toggleBtn = document.querySelector('.toggle-sidebar, #toggleSidebar, [onclick*="toggleSidebar"]');
    if (toggleBtn) toggleBtn.style.display = '';
});


</script>
@endpush
