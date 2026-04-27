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

        #unit-container tr:hover {
            background-color: #f8f9fa;
        }

        /* Badge Merk Custom Style */
        .badge-merk {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .merk-honda { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .merk-yamaha { background-color: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .merk-suzuki { background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
        .merk-kawasaki { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .merk-lainnya { background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

        .btn-action-custom {
            padding: 0.25rem 0.5rem;
            font-size: 1.1rem;
            line-height: 1;
            transition: all 0.2s;
        }
    </style>

    <div class="container-fluid px-4">
        {{-- Header & Filter Card --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-bicycle me-2 text-primary"></i>Master Tipe Unit
                        </h5>
                        <small class="text-muted">Manajemen daftar merk dan tipe kendaraan pelanggan</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-primary btn-sm px-3" onclick="openAddModal()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Unit Baru
                        </button>
                    </div>
                </div>

                <hr class="my-3 opacity-25">

                <div class="row g-2">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" id="search-unit" class="form-control border-start-0 ps-0"
                                placeholder="Cari merk atau tipe motor...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filter-merk">
                            <option value="">SEMUA MERK</option>
                            <option value="HONDA">HONDA</option>
                            <option value="YAMAHA">YAMAHA</option>
                            <option value="SUZUKI">SUZUKI</option>
                            <option value="KAWASAKI">KAWASAKI</option>
                            <option value="LAINNYA">LAINNYA</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive" id="scroll-container" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 8%;" class="text-center">No</th>
                                <th style="width: 25%;">Merk Kendaraan</th>
                                <th style="width: 52%;">Nama Unit / Tipe</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="unit-container">
                            {{-- Data di-load via JavaScript --}}
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
                        Menampilkan 0 dari 0 unit
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="modalUnit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalUnitLabel">Tambah Unit Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUnit">
                    @csrf
                    <input type="hidden" name="id" id="modal_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small">Merk Kendaraan</label>
                                <select name="merk" id="modal_merk" class="form-select" required>
                                    <option value="">Pilih Merk</option>
                                    <option value="HONDA">HONDA</option>
                                    <option value="YAMAHA">YAMAHA</option>
                                    <option value="SUZUKI">SUZUKI</option>
                                    <option value="KAWASAKI">KAWASAKI</option>
                                    <option value="LAINNYA">LAINNYA</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Nama Unit / Tipe Motor</label>
                                <input type="text" name="nama_unit" id="modal_nama_unit" class="form-control"
                                    placeholder="CONTOH: VARIO 150 CBS" required style="text-transform: uppercase;">
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
@endsection

@push('scripts')
<script>
    // Gunakan objek state untuk manajemen data
    let state = {
        currentPage: 1,
        isLoading: false,
        hasMoreData: true,
        container: document.getElementById('unit-container'),
        scrollBox: document.getElementById('scroll-container')
    };

    /**
     * LOAD DATA (AJAX FETCH)
     */
    async function fetchUnits(append = false) {
        if (state.isLoading || (append && !state.hasMoreData)) return;
        if (!append) { state.currentPage = 1; state.hasMoreData = true; }

        state.isLoading = true;
        const loadingEl = document.getElementById('loading-state');
        if (loadingEl) loadingEl.classList.remove('d-none');

        try {
            const params = new URLSearchParams({
                page: state.currentPage,
                search: document.getElementById('search-unit').value,
                merk: document.getElementById('filter-merk').value
            });

            // Ganti URL dengan helper route agar presisi
            const res = await fetch(`{{ route('unit.get-data') }}?${params}`, {
                headers: { 'Accept': 'application/json' } // Memastikan server kirim JSON
            });

            // Validasi apakah respon benar-benar JSON
            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText.includes('<!DOCTYPE') ? "Server Error (HTML)" : errorText);
            }

            const result = await res.json();
            renderData(result.data || [], append, result.total || 0);

            state.hasMoreData = !!result.next_page_url;
            if (state.hasMoreData) state.currentPage++;

        } catch (e) {
            console.error("Fetch Error:", e);
            // Jika data kosong saat search, bersihkan container
            if (!append) state.container.innerHTML = '<tr><td colspan="4" class="text-center p-5 text-danger">Terjadi kesalahan memuat data.</td></tr>';
        } finally {
            state.isLoading = false;
            if (loadingEl) loadingEl.classList.add('d-none');
        }
    }

    /**
     * RENDER KE TABEL
     */
    function renderData(data, append, total) {
        let html = '';
        if (data.length === 0 && !append) {
            state.container.innerHTML = '<tr><td colspan="4" class="text-center p-5 text-muted">Data tidak ditemukan</td></tr>';
            document.getElementById('info-pencatatan').innerHTML = `Menampilkan 0 dari 0 unit`;
            return;
        }

        data.forEach((item, index) => {
            const rowNo = append ? state.container.querySelectorAll('tr').length + index + 1 : index + 1;
            const itemJson = encodeURIComponent(JSON.stringify(item));
            const merkClass = `merk-${(item.merk || 'lainnya').toLowerCase()}`;

            html += `
                <tr>
                    <td class="text-center text-muted small">${rowNo}</td>
                    <td><span class="badge-merk ${merkClass}">${item.merk}</span></td>
                    <td class="fw-bold text-dark text-uppercase">${item.nama_unit}</td>
                    <td class="text-center">
                        <button class="btn btn-action-custom text-warning" onclick="prepEdit('${itemJson}')" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-action-custom text-danger" onclick="deleteUnit(${item.id})" title="Hapus">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </td>
                </tr>`;
        });

        if (append) state.container.insertAdjacentHTML('beforeend', html);
        else state.container.innerHTML = html;

        const count = state.container.querySelectorAll('tr').length;
        document.getElementById('info-pencatatan').innerHTML = `Menampilkan <strong>${count}</strong> dari <strong>${total}</strong> unit`;
    }

    /**
     * GLOBAL FUNCTIONS (Harus di luar DOMContentLoaded)
     */
    window.openAddModal = function() {
        const form = document.getElementById('formUnit');
        form.reset();
        document.getElementById('modal_id').value = '';
        document.getElementById('modalUnitLabel').innerText = 'TAMBAH UNIT BARU';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalUnit')).show();
    };

    window.prepEdit = function(json) {
        const item = JSON.parse(decodeURIComponent(json));
        document.getElementById('modal_id').value = item.id;
        document.getElementById('modal_merk').value = item.merk;
        document.getElementById('modal_nama_unit').value = item.nama_unit;
        document.getElementById('modalUnitLabel').innerText = 'EDIT DATA UNIT';
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalUnit')).show();
    };

  window.deleteUnit = function(id) {
    Swal.fire({
        title: 'Hapus Unit?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Gunakan URL yang konsisten dengan web.php
            fetch(`{{ url('master/unit') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok) {
                    Swal.fire('Berhasil', data.message, 'success');
                    fetchUnits(false); // Refresh list
                } else {
                    Swal.fire('Gagal', data.message || 'Error server', 'error');
                }
            })
            .catch(err => console.error(err));
        }
    });
};

    /**
     * INIT & EVENTS
     */
    document.addEventListener('DOMContentLoaded', () => {
        fetchUnits();

        // Search & Filter dengan Debounce 500ms
        let timer;
        ['search-unit', 'filter-merk'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fetchUnits(false), 500);
                });
            }
        });

    // INFINTE SCROLL
        state.scrollBox.addEventListener('scroll', () => {
    if (state.isLoading || !state.hasMoreData) return;

    // Cek jika sudah scroll sampai bawah
    if (state.scrollBox.scrollTop + state.scrollBox.clientHeight >= state.scrollBox.scrollHeight - 20) {
        fetchUnits(true); // Load halaman berikutnya
    }
});


        // Submit Form (Handle Add & Edit)
        document.getElementById('formUnit').addEventListener('submit', async function(e) {
    e.preventDefault();

    const id = document.getElementById('modal_id').value;
    const loadingBtn = e.submitter; // Tombol simpan

    // Tentukan URL
    let url = id ? `{{ url('master/unit') }}/${id}` : "{{ route('unit.store') }}";

    const formData = new FormData(this);

    // Jika EDIT, tambahkan spoofing method untuk Laravel
    if (id) {
        formData.append('_method', 'PUT');
    }

    // Nonaktifkan tombol agar tidak double submit
    if (loadingBtn) loadingBtn.disabled = true;

    try {
        const res = await fetch(url, {
            method: 'POST', // Gunakan POST baik untuk tambah maupun edit (diakali _method)
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        });

        // Cek apakah response berupa JSON
        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const textError = await res.text();
            console.error("Server returned non-JSON:", textError);
            throw new Error("Server mengirimkan format yang salah (HTML). Periksa log Laravel.");
        }

        const result = await res.json();

        if (res.ok && result.status === 'success') {
            const modalEl = document.getElementById('modalUnit');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            Swal.fire('Berhasil', result.message, 'success');
            this.reset();
            fetchUnits(false); // Refresh tabel
        } else {
            let errorMsg = result.message || "Terjadi kesalahan validasi";
            if (result.errors) {
                errorMsg = Object.values(result.errors).flat().join('<br>');
            }
            Swal.fire('Gagal', errorMsg, 'error');
        }
    } catch (err) {
        console.error("Submit Error:", err);
        Swal.fire('Error', err.message, 'error');
    } finally {
        if (loadingBtn) loadingBtn.disabled = false;
    }
});
    });

    // Auto-uppercase
    document.getElementById('modal_nama_unit').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

</script>
@endpush
