@extends('layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ==================================================
MASTER KONSUMEN PREMIUM RESPONSIVE
================================================== */

/* desktop default */
#scroll-container{
    height:calc(100vh - 350px);
    overflow-y:auto;
    overflow-x:hidden;
    border:1px solid #e9ecef;
    border-radius:10px;
}

table{
    table-layout:fixed !important;
    width:100% !important;
}

thead th{
    background:#f8fafc !important;
    color:#475569 !important;
    text-transform:uppercase;
    font-size:.75rem;
    letter-spacing:.025em;
    padding:12px 15px !important;
    border-bottom:2px solid #e2e8f0 !important;
}

tbody td{
    border-bottom:1px solid #f1f5f9 !important;
    font-size:14px !important;
    padding:12px 15px !important;
    vertical-align:middle !important;
}

.no-badge{
    display:inline-block;
    width:28px;
    height:28px;
    line-height:28px;
    background:#f1f5f9;
    color:#64748b;
    border-radius:6px;
    font-weight:700;
    font-size:11px;
}

.dataTables_info{
    padding:20px !important;
    font-size:13px !important;
    color:#64748b !important;
    font-weight:500 !important;
}

.btn-action-custom{
    padding:5px 8px;
    border:none;
    background:transparent;
    transition:.2s;
}

.btn-action-custom:hover{
    background:#f8f9fa;
    transform:scale(1.08);
}

/* ==================================================
MODAL RIWAYAT + HIDE TOGGLE SIDEBAR
================================================== */
#modalRiwayat.show{
    display:block !important;
    background:rgba(0,0,0,.55);
    z-index:9999;
}

body.modal-open .sidebar-toggle,
body.modal-open #sidebarToggle,
body.modal-open .navbar-toggler,
body.modal-open button[onclick*="toggleSidebar"]{
    display:none !important;
}

/* ==================================================
MOBILE
================================================== */
@media (max-width:768px){

    .container-fluid{
        padding-left:10px !important;
        padding-right:10px !important;
    }

    /* header */
    .card{
        border-radius:16px !important;
    }

    .card-body{
        padding:14px !important;
    }

    h5{
        font-size:18px !important;
        line-height:1.35;
    }

    /* tombol tambah */
    .btn-primary.btn-sm{
        width:100%;
        min-height:46px;
        font-size:14px;
        border-radius:10px;
        margin-top:12px;
    }

    /* search */
    .input-group-text,
    #search-konsumen{
        height:46px;
        font-size:14px;
    }

    /* table utama */
    #scroll-container{
        height:auto !important;
        max-height:none !important;
        overflow-x:auto !important;
        overflow-y:auto !important;
        -webkit-overflow-scrolling:touch;
        border-radius:12px;
    }

    #tabel-konsumen{
        min-width:920px !important;
    }

    #tabel-konsumen th,
    #tabel-konsumen td{
        white-space:nowrap !important;
        font-size:13px !important;
        padding:10px !important;
    }

    #scroll-container::after{
        content:"Geser tabel ke samping ← →";
        display:block;
        text-align:center;
        font-size:11px;
        color:#6c757d;
        padding:8px;
        border-top:1px solid #eee;
        background:#fafafa;
    }

    /* modal tambah konsumen */
    #modalKonsumen .modal-dialog{
        margin:0 !important;
        max-width:100% !important;
        width:100% !important;
        height:80vh !important;
    }

    #modalKonsumen .modal-content{
        height:80vh !important;
        border-radius:0 !important;
    }

    #modalKonsumen .modal-body{
        overflow-y:auto !important;
    }

    /* modal riwayat fullscreen */
    #modalRiwayat{
        padding:0 !important;
    }

    #modalRiwayat .modal-dialog{
        margin:0 !important;
        width:100% !important;
        max-width:100% !important;
        height:80vh !important;
    }

    #modalRiwayat .modal-content{
        height:80vh !important;
        border-radius:0 !important;
        display:flex;
        flex-direction:column;
    }

    #modalRiwayat .modal-header{
        flex-shrink:0;
        padding:14px !important;
    }

    #modalRiwayat .modal-body{
        flex:1;
        overflow-y:auto !important;
        overflow-x:hidden !important;
        padding:12px !important;
    }

    /* tabel riwayat ajax */
    #modalRiwayat table{
        min-width:760px !important;
        width:760px !important;
    }

    #modalRiwayat .table-responsive{
        overflow-x:auto !important;
        -webkit-overflow-scrolling:touch;
        border-radius:10px;
    }

    #modalRiwayat .table-responsive::after{
        content:"Geser tabel riwayat ← →";
        display:block;
        text-align:center;
        font-size:11px;
        color:#6c757d;
        padding:7px;
        border-top:1px solid #eee;
        background:#fafafa;
    }
    /* RIWAYAT TABLE REAL SCROLL */
#modalRiwayat .riwayat-table-scroll{
    width:100%;
    overflow-x:auto !important;
    overflow-y:hidden !important;
    -webkit-overflow-scrolling:touch;
}
#modalRiwayat .riwayat-table-scroll th,
#modalRiwayat .riwayat-table-scroll td{
    white-space:nowrap !important;
    padding:10px !important;
}

#modalRiwayat .riwayat-table-scroll th:first-child,
#modalRiwayat .riwayat-table-scroll td:first-child,
#modalRiwayat .riwayat-table-scroll th:nth-child(2),
#modalRiwayat .riwayat-table-scroll td:nth-child(2){
    min-width:260px;
    white-space:normal !important;
}
#modalRiwayat .riwayat-table-scroll table{
    min-width:820px !important;
    width:820px !important;
    margin-bottom:0 !important;
}


}


</style>

<div class="container-fluid px-4">

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-people-fill me-2 text-primary"></i>
                        Master Data Konsumen
                    </h5>
                    <small class="text-muted">
                        Manajemen data konsumen dan riwayat servis kendaraan
                    </small>
                </div>

                <button class="btn btn-primary btn-sm px-3" onclick="openAddModal()">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Konsumen
                </button>
            </div>

            <hr class="my-3 opacity-25">

            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>

                <input type="text"
                       id="search-konsumen"
                       class="form-control border-start-0 ps-0"
                       placeholder="Cari nama, nopol, atau telepon...">
            </div>

        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">

            <div id="scroll-container">
                <table class="table table-hover align-middle mb-0" id="tabel-konsumen">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="25%">Nama Pelanggan</th>
                            <th width="30%">Unit / Kendaraan</th>
                            <th width="15%">No Whatsapp</th>
                            <th width="15%">Alamat</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="konsumen-container"></tbody>
                </table>

                <div id="loading-state" class="text-center p-4 d-none">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                    <span class="ms-2 small text-muted">Memuat data...</span>
                </div>
            </div>

        </div>

        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <div class="small text-muted" id="info-pencatatan">
                    Menampilkan 0 konsumen
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL KONSUMEN -->
<div class="modal fade" id="modalKonsumen" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark text-uppercase"
                    id="modalKonsumenLabel">
                    REGISTRASI KONSUMEN
                </h6>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formKonsumen">
                @csrf
                <input type="hidden" name="id" id="modal_id">

                <div class="modal-body p-4">

                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_konsumen" id="modal_nama"
                               class="form-control text-uppercase" required>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-2">No WhatsApp</label>
                        <input type="number" name="no_wa" id="modal_wa"
                               class="form-control" required>
                    </div>

                    <div>
                        <label class="small fw-bold text-muted mb-2">Alamat</label>
                        <textarea name="alamat" id="modal_alamat"
                                  class="form-control text-uppercase"
                                  rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit"
                            class="btn btn-primary w-100 fw-bold py-2"
                            id="btnSimpan">
                        SIMPAN DATA
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- MODAL RIWAYAT -->
<div class="modal fade" id="modalRiwayat" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    Riwayat <span class="text-primary">Servis</span>
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="isiRiwayat"></div>

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
        container: document.getElementById('konsumen-container'),
        scrollBox: document.getElementById('scroll-container')
    };

    /** FETCH DATA **/
    async function fetchKonsumen(append = false) {
        if (state.isLoading || (append && !state.hasMoreData)) return;
        if (!append) { state.currentPage = 1; state.hasMoreData = true; }

        state.isLoading = true;
        document.getElementById('loading-state').classList.remove('d-none');

        try {
            const params = new URLSearchParams({
                page: state.currentPage,
                search: document.getElementById('search-konsumen').value
            });

           const res = await fetch(`{{ route('konsumen.get-data') }}?${params}`, {
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
            state.container.innerHTML = '<tr><td colspan="6" class="text-center p-5 text-muted">Data konsumen tidak ditemukan</td></tr>';
            return;
        }

        data.forEach((item, index) => {
            const rowNo = append ? state.container.querySelectorAll('tr').length + index + 1 : index + 1;
            const itemJson = encodeURIComponent(JSON.stringify(item));

            // Render Badge Kendaraan
            let kendaraanHtml = '<span class="text-muted small italic">Belum ada unit</span>';
            if (item.kendaraans && item.kendaraans.length > 0) {
                kendaraanHtml = item.kendaraans.map(v => `
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle badge-unit" onclick="viewHistory(${v.id})">
                        [${v.no_polisi.toUpperCase()}] ${v.unit ? v.unit.nama_unit.toUpperCase() : 'N/A'}
                    </span>`).join(' ');
            }

            html += `
                <tr>
                    <td class="text-center text-muted small">${rowNo}</td>
                    <td><div class="fw-bold text-dark text-uppercase">${item.nama_konsumen}</div></td>
                    <td>${kendaraanHtml}</td>
                    <td>
                        <a href="https://wa.me/${item.no_wa}" target="_blank" class="text-success small fw-bold text-decoration-none">
                            <i class="bi bi-whatsapp"></i> ${item.no_wa}
                        </a>
                    </td>
                    <td><span class="text-muted small text-uppercase">${item.alamat || '-'}</span></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <button class="btn btn-action-custom text-warning" onclick="prepEdit('${itemJson}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-action-custom text-danger" onclick="deleteKonsumen(${item.id})">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
        });

        if (append) state.container.insertAdjacentHTML('beforeend', html);
        else state.container.innerHTML = html;

        document.getElementById('info-pencatatan').innerHTML = `Menampilkan <strong>${state.container.querySelectorAll('tr').length}</strong> dari <strong>${total}</strong> konsumen`;
    }


   /** MODAL HANDLERS **/
   window.openAddModal = function() {
    const form = document.getElementById('formKonsumen');
    if (form) {
        form.reset(); // Bersihkan semua input text/textarea
    }

    const modalId = document.getElementById('modal_id');
    if (modalId) {
        modalId.value = ''; // Pastikan ID kosong
    }

    const label = document.getElementById('modalKonsumenLabel');
    if (label) {
        label.innerText = 'REGISTRASI KONSUMEN'; // Kembalikan judul modal
    }

    const modalEl = document.getElementById('modalKonsumen');
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
};

window.prepEdit = function(json) {
    const item = JSON.parse(decodeURIComponent(json));

    // Gunakan pengecekan keberadaan elemen sebelum set .value
    const fields = {
        'modal_id': item.id,
        'modal_nama': item.nama_konsumen,
        'modal_wa': item.no_wa,
        'modal_alamat': item.alamat || ''
    };

    for (const [id, value] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (el) el.value = value; // Ini mencegah error 'null'
    }

    const modalLabel = document.getElementById('modalKonsumenLabel');
    if (modalLabel) modalLabel.innerText = 'EDIT DATA KONSUMEN';

    const modalEl = document.getElementById('modalKonsumen');
    if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).show();
};
    window.deleteKonsumen = function(id) {
        // Cek apakah Swal tersedia
        if (typeof Swal === 'undefined') {
            if (confirm('Hapus konsumen?')) {
                executeDelete(id);
            }
            return;
        }

        Swal.fire({
            title: 'Hapus Konsumen?',
            text: "Seluruh riwayat servis pelanggan ini akan ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                executeDelete(id);
            }
        });
    };

    // Fungsi pembantu delete agar kode lebih bersih
    function executeDelete(id) {
        fetch(`{{ url('master/konsumen/delete') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Berhasil', data.message, 'success');
            } else {
                alert(data.message);
            }
            fetchKonsumen(false);
        })
        .catch(err => console.error("Error Delete:", err));
    }
    window.viewHistory = async function(kendaraanId) {
    const modalEl = document.getElementById('modalRiwayat');
    const containerRiwayat = document.getElementById('isiRiwayat');

    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
    modalInstance.show();

    containerRiwayat.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2 small text-muted">Mengambil data riwayat...</p>
        </div>
    `;

    try {
        const res = await fetch(`{{ url('master/konsumen/riwayat-kendaraan') }}/${kendaraanId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!res.ok) throw new Error('Gagal mengambil data');

        const html = await res.text();

        containerRiwayat.innerHTML = `
            <div class="table-responsive riwayat-scroll-wrap">
                ${html}
            </div>
        `;

    } catch (err) {
        containerRiwayat.innerHTML = `
            <div class="alert alert-danger m-3">
                Gagal memuat riwayat servis.
            </div>
        `;
    }
};

    /** INIT & EVENTS **/
    document.addEventListener('DOMContentLoaded', () => {
        fetchKonsumen();

        // Infinite Scroll
        state.scrollBox.addEventListener('scroll', () => {
            if (state.scrollBox.scrollTop + state.scrollBox.clientHeight >= state.scrollBox.scrollHeight - 20) {
                fetchKonsumen(true);
            }
        });

        // Search Debounce
        let timer;
        document.getElementById('search-konsumen').addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => fetchKonsumen(false), 500);
        });

        // Submit Form (Add/Update)
    document.getElementById('formKonsumen').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('modal_id').value;
    const btnSimpan = e.submitter;

    let url = id ? `{{ url('master/konsumen/update') }}/${id}` : "{{ route('konsumen.store') }}";

    const formData = new FormData(this);
    if (id) {
        formData.append('_method', 'PUT');
    }

    if (btnSimpan) btnSimpan.disabled = true;

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await res.json();

        if (res.ok) {
            // 1. Sembunyikan Modal
            const modalEl = document.getElementById('modalKonsumen');
            bootstrap.Modal.getInstance(modalEl).hide();

            // 2. Tampilkan Notifikasi
            Swal.fire('Berhasil', result.message, 'success');

            // 3. RESET FORM TOTAL (Penting!)
            this.reset();
            document.getElementById('modal_id').value = ''; // Kosongkan ID agar tidak dianggap update lagi
            document.getElementById('modalKonsumenLabel').innerText = 'REGISTRASI KONSUMEN';

            // 4. Refresh Tabel
            fetchKonsumen(false);
        } else {
            let msg = result.message || "Gagal menyimpan data";
            if(result.errors) msg = Object.values(result.errors).flat().join('<br>');
            Swal.fire('Error', msg, 'error');
        }

    } catch (err) {
        console.error("Submit Error:", err);
        Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error');
    } finally {
        if (btnSimpan) btnSimpan.disabled = false;
    }
});

    });
</script>
@endpush
