@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
    <style>
        /* Input readonly tetap hitam pekat */
        .form-control[readonly],
        select[style*="pointer-events: none"] {
            background-color: #e9ecef !important;
            color: #000000 !important;
            -webkit-text-fill-color: #000000;
            /* Untuk browser safari/chrome tertentu */
        }

        /* =========================
   MODE MOBILE ONLY
   Tidak ganggu desktop
========================= */
@media (max-width: 768px) {

    .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    h4 {
        font-size: 18px;
    }

    .card {
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .card h6 {
        font-size: 13px;
    }

    .form-control,
    .form-select,
    .btn {
        min-height: 46px;
        font-size: 15px;
    }

    .btn-sm {
        min-height: 42px;
    }

    /* total pembayaran sticky bawah */
    .col-md-4.text-end {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 10px;
        z-index: 20;
        border-top: 1px solid #ddd;
    }

    #label-total-bawah {
        font-size: 22px !important;
        padding: 8px 12px !important;
    }

    /* tombol bayar besar */
    .btn-lg {
        font-size: 18px;
        padding: 14px;
        border-radius: 12px;
    }

    /* modal full mobile */
    .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100%;
    }

    .modal-content {
        min-height: 80vh;
        border-radius: 0;
    }

    /* table jadi rapat */
    table th,
    table td {
        font-size: 13px;
        padding: 8px;
    }

    /* qty part */
    .qty-part {
        width: 60px !important;
        height: 42px;
    }

    .mobile-toggle{
        transition: .2s ease;
    }

}


    </style>

    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0">Input <span class="text-primary">Layanan & Sparepart</span></h4>
            <a href="/" class="btn btn-light btn-sm border">Batal</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
        @endif

        <form action="{{ route('transaksi.simpan') }}" method="POST" onsubmit="disableButton()" id="form-transaksi" >
            @csrf

            <div class="mb-4">
                <button type="button" class="btn btn-outline-secondary btn-sm shadow-sm" id="btn-toggle-pelanggan">
                    <i class="bi bi-person-x me-1"></i> Tanpa Data Pelanggan
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-4" id="section-kiri">
                    <div class="card p-3 shadow-sm border-0 h-100">
                        <h6 class="fw-bold small border-bottom pb-2 mb-3 text-uppercase text-secondary">1. Informasi Petugas
                            & Unit</h6>

                        <div class="mb-3">
                            <label class="small fw-bold">No. WhatsApp</label>
                            <input type="number" name="no_wa" id="no_wa" class="form-control bg-light border-0">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">Nama Pelanggan</label>
                            <span id="ket_nama" class="badge rounded-pill ml-2"></span>
                            <input type="text" id="nama_pelanggan" name="nama_pelanggan"
                                class="form-control bg-light border-0" required style="text-transform: uppercase;">
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold">No. Polisi</label>
                            <input type="text" name="no_polisi" id="no_polisi" class="form-control bg-light border-0"
                                required style="text-transform: uppercase;">
                        </div>

                      <div class="mb-3">
    <label class="small fw-bold text-secondary">Merk Kendaraan</label>
    <select id="select-merk" name="merk" class="form-select" onchange="filterTipeByMerk()">
        <option value="">-- Pilih Merk --</option>
        @foreach($merks as $m)
            {{-- Pastikan value dan label hanya mengambil properti merk --}}
            <option value="{{ $m->merk }}">{{ $m->merk }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
                            <label class="small fw-bold text-secondary">Tipe Kendaraan</label>
                            <div class="input-group">
                                <select name="unit_id" id="select-tipe" class="form-select" disabled required>
                                    <option value="">-- Pilih Tipe --</option>
                                </select>
                                <button class="btn btn-outline-primary" type="button" onclick="bukaModalTambahUnit()"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="small fw-bold text-primary">Mekanik</label>
                            <select name="mekanik_id" id="mekanik_id" class="form-select border" required>
                                <option value="">-- Pilih Mekanik --</option>
                                @foreach ($mekaniks as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_mekanik }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" id="section-jasa">
                    <div class="card p-3 shadow-sm border-0 h-100">
                        <h6 class="font-weight-bold small mb-3 text-uppercase border-bottom pb-2 text-secondary">2. Pilih
                           Jasa / Servis</h6>
                <div id="wrapper-jasa-terpilih">
            </div>

        <button type="button" class="btn btn-primary btn-sm w-100 shadow-sm mt-2" onclick="bukaMasterJasa()">
            <i class="bi bi-search me-1"></i> Cari Jasa / Servis
        </button>

    </div>
</div>

                <div class="col-md-4" id="section-part">
                    <div class="card p-3 shadow-sm border-0 h-100">
                        <h6 class="font-weight-bold small mb-3 text-uppercase border-bottom pb-2 text-secondary">3. Pilih
                            Sparepart</h6>
                        <div id="wrapper-part">
                        </div>
                        <button type="button" class="btn btn-primary btn-sm w-100 shadow-sm mt-2"
                            onclick="bukaMasterPart()">
                            <i class="bi bi-search me-1"></i> Cari di Master Data
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4 text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <h6 class="fw-bold small text-muted mb-0 me-3">TOTAL PEMBAYARAN</h6>
                        <div class="d-flex align-items-center">
                            <span class="bg-primary text-white fw-bold px-2 py-1 rounded-start">Rp</span>
                            <h2 class="fw-bold mb-0 px-3 py-1 border border-start-0 rounded-end bg-white"
                                id="label-total-bawah">0
                            </h2>
                            <input type="hidden" name="total_harga" id="input-total-hidden" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-sm"
                        onclick="validasiDanBukaModal()">
                        <i class="bi bi-cart-check me-2"></i> BAYAR SEKARANG
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modalTambahUnit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Tambah Tipe Kendaraan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-tambah-unit">
                    <div class="modal-body p-4">
                       <div class="mb-3">
    <label class="fw-bold small">Merk Kendaraan</label>
    <select id="new_merk" class="form-control" required>
        <option value="">-- Pilih Merk --</option>
        @foreach($merks as $m)
            <option value="{{ $m->merk }}">{{ strtoupper($m->merk) }}</option>
        @endforeach
    </select>
</div>
                        <div class="mb-3">
                            <label class="fw-bold small">Nama Tipe / Unit</label>
                            <input type="text" id="new_nama_unit" class="form-control" placeholder="Contoh: VARIO 150" required style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" onclick="simpanUnitBaru()">Simpan Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMasterPart" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2"></i>Cari Sparepart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="text" id="searchPart" class="form-control mb-3"
                        placeholder="Ketik nama atau kode sparepart...">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle border">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th>Info Produk</th>
                                    <th>Harga Jual</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach ($list_part as $p)
        {{-- Tambahan proteksi: Hanya tampilkan jika status == 1 --}}
        @if($p->status == 1)
            <tr class="item-baris-part"
                data-nama="{{ strtolower($p->nama_part) }} {{ strtolower($p->kode_part) }}">
                <td>
                    <span class="fw-bold">{{ $p->nama_part }}</span><br>
                    <small class="text-muted">{{ $p->kode_part }}</small>
                </td>
                <td class="text-primary fw-bold">
                    Rp {{ number_format($p->harga_jual, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="badge {{ $p->stok <= 0 ? 'bg-danger' : 'bg-success' }}">
                        {{ $p->stok }}
                    </span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-primary px-3"
                        {{ $p->stok <= 0 ? 'disabled' : '' }}
                        onclick="pilihDariMaster('{{ $p->id }}', '{{ $p->nama_part }}', '{{ $p->harga_jual }}', '{{ $p->stok }}')">
                        Pilih
                    </button>
                </td>
            </tr>
        @endif
    @endforeach
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <div class="modal fade" id="modalMasterJasa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-wrench-adjustable me-2"></i>Cari Jasa / Servis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="text" id="searchJasa" class="form-control mb-3"
                    placeholder="Ketik nama atau kategori jasa...">

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" class="btn btn-dark btn-sm filter-jasa-btn active" data-filter="all" onclick="filterKategoriJasa('all', this)">Semua</button>
                    <button type="button" class="btn btn-outline-dark btn-sm filter-jasa-btn" data-filter="bubut" onclick="filterKategoriJasa('bubut', this)">Bubut</button>
                    <button type="button" class="btn btn-outline-dark btn-sm filter-jasa-btn" data-filter="matic" onclick="filterKategoriJasa('matic', this)">Matic</button>
                    <button type="button" class="btn btn-outline-dark btn-sm filter-jasa-btn" data-filter="bebek" onclick="filterKategoriJasa('bebek', this)">Bebek</button>
                    <button type="button" class="btn btn-outline-dark btn-sm filter-jasa-btn" data-filter="sport" onclick="filterKategoriJasa('sport', this)">Sport</button>
                </div>

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover align-middle border">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th>Info Layanan</th>
                                <th>Harga Jual</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jasas as $j)
                                <tr class="item-baris-jasa"
                                    data-nama="{{ strtolower($j->nama_jasa) }}"
                                    data-kategori="{{ strtolower($j->kategori) }}">
                                    <td>
                                        <span class="fw-bold">{{ strtoupper($j->nama_jasa) }}</span><br>
                                        <small class="text-muted text-uppercase">{{ $j->kategori }}</small>
                                    </td>
                                    <td class="text-primary fw-bold">
                                        Rp {{ number_format($j->biaya_jasa, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">
                                            {{ strtoupper($j->kategori) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-3"
                                            onclick="pilihJasaKeList('{{ $j->id }}', '{{ $j->nama_jasa }}', '{{ $j->biaya_jasa }}')">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="modalBayar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <h6 class="text-muted mb-1 small text-uppercase">Total Tagihan</h6>
                    <h2 class="fw-bold text-dark mb-4">Rp <span id="modal-label-total">0</span></h2>

                    <div class="text-start mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="fw-bold small">Uang Tunai</label>
                            <button type="button"
                                class="btn btn-sm btn-link text-success p-0 fw-bold text-decoration-none"
                                onclick="setUangPas()">UANG PAS</button>
                        </div>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted">Rp</span>
                            <input type="number" id="input-bayar" name="bayar" form="form-transaksi"
                                class="form-control form-control-lg bg-light border-0 fw-bold" placeholder="0"
                                onkeyup="hitungKembalian()" required>
                        </div>
                    </div>

                    <div class="p-3 rounded bg-light border">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small text-muted">KEMBALIAN</span>
                            <h4 class="fw-bold mb-0 text-primary">Rp <span id="label-kembali">0</span></h4>
                        </div>
                    </div>
                    <div id="pesan-error" class="alert alert-danger mt-3 small d-none py-2">⚠️ Uang kurang!</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="form-transaksi" id="btn-submit-form"
                        class="btn btn-success fw-bold px-4">
                        <i class="bi bi-printer me-1"></i> BAYAR & CETAK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
   <script>
// ==========================================
// 1. DATA GLOBAL & INISIALISASI
// ==========================================
// Pastikan variabel $units dikirim dari Controller
const globalUnits = @json($units);

$(document).ready(function() {
    // ==========================================
    // 2. LOGIKA VALIDASI (WA & NO POLISI)
    // ==========================================

    // Cek WhatsApp
    $('#no_wa').on('input', function() {
        let wa = $(this).val();
        $('.status-wa').html('');

        if (wa.length >= 10) {
            $.ajax({
                url: "{{ route('transaksi.cek-konsumen') }}",
                type: 'GET',
                data: { no_wa: wa },
                success: function(res) {
                    if (res.status === 'found') {
                        $('#nama_pelanggan').val(res.nama || res.nama_konsumen)
                            .prop('readonly', true)
                            .css('background-color', '#e9ecef');
                        $('.status-wa').html('<span class="badge bg-success small">Pelanggan Lama</span>');
                    } else {
                        $('#nama_pelanggan').prop('readonly', false).css('background-color', '#fff');
                    }
                }
            });
        } else {
            $('#nama_pelanggan').val('').prop('readonly', false).css('background-color', '#fff');
        }
    });

    // Cek No Polisi
    $('#no_polisi').on('input', function() {
        let nopol = $(this).val().toUpperCase().replace(/\s/g, '');
        $('.status-nopol').html('');

        if (nopol.length >= 4) {
            let urlNopol = "{{ route('transaksi.cek-kendaraan-lengkap', ':nopol') }}".replace(':nopol', nopol);
            $.ajax({
                url: urlNopol,
                type: 'GET',
                success: function(res) {
                    if (res.status === 'found') {
                        $('#select-merk').val(res.merk)
                            .css({'pointer-events': 'none', 'background-color': '#e9ecef'})
                            .attr('tabindex', '-1');

                        renderTipeByMerk(res.merk, res.unit_id);
                        $('.status-nopol').html('<span class="badge bg-success small">Kendaraan Terdaftar</span>');
                    } else {
                        bukaKunciKendaraan();
                    }
                }
            });
        }
    });

    // Reset jika nopol dihapus
    $('#no_polisi').on('keyup', function(e) {
        if ($(this).val() === "" || e.keyCode === 8) {
            if($(this).val().length < 4) {
                bukaKunciKendaraan();
                $('.status-nopol').html('');
            }
        }
    });

    // ==========================================
    // 3. SEARCH & TOGGLE UI
    // ==========================================

    $('#searchPart').on('keyup', function() {
        let key = $(this).val().toLowerCase();
        $('.item-baris-part').each(function() {
            $(this).toggle($(this).data('nama').toString().toLowerCase().includes(key));
        });
    });

    $('#searchJasa').on('keyup', function() {
        filterKategoriJasa('active_check');
    });

    $('#btn-toggle-pelanggan').on('click', function() {
        const isHidden = $('#section-kiri').toggleClass('d-none').hasClass('d-none');
        if (isHidden) {
            $(this).html('<i class="bi bi-person-plus me-1"></i> Pakai Data Pelanggan').addClass('btn-primary').removeClass('btn-outline-secondary');
            $('#section-jasa, #section-part').removeClass('col-md-4').addClass('col-md-6');
            $('#section-kiri').find('input, select').val('').removeAttr('required');
        } else {
            $(this).html('<i class="bi bi-person-x me-1"></i> Tanpa Data Pelanggan').addClass('btn-outline-secondary').removeClass('btn-primary');
            $('#section-jasa, #section-part').removeClass('col-md-6').addClass('col-md-4');
            $('#no_polisi, #nama_pelanggan').attr('required', true);
        }
    });
});

// ==========================================
// 4. FUNGSI GLOBAL (LOGIKA UNIT)
// ==========================================

function filterTipeByMerk() {
    const merk = $('#select-merk').val();
    renderTipeByMerk(merk);
}

function renderTipeByMerk(merk, selectedId = null) {
    const $selectTipe = $('#select-tipe');

    // PERBAIKAN: Buang atribut disabled agar dropdown bisa terbuka dan dipilih
    $selectTipe.removeAttr('disabled');

    $selectTipe.empty().append('<option value="">-- Pilih Tipe --</option>');

    if (merk) {
        // Filter data units (Case Insensitive)
        const filtered = globalUnits.filter(u =>
            u.merk.toString().trim().toUpperCase() === merk.toString().trim().toUpperCase()
        );

        filtered.forEach(unit => {
            let isSelected = (selectedId && unit.id == selectedId) ? 'selected' : '';
            $selectTipe.append(`<option value="${unit.id}" ${isSelected}>${unit.nama_unit.toUpperCase()}</option>`);
        });

        // Kunci jika otomatis (data lama), buka jika manual (input baru)
        if (selectedId) {
            $selectTipe.css({'pointer-events': 'none', 'background-color': '#e9ecef'}).attr('tabindex', '-1');
        } else {
            $selectTipe.css({'pointer-events': 'auto', 'background-color': '#fff'}).removeAttr('tabindex');
        }
    }
}

function bukaKunciKendaraan() {
    $('#select-merk').val('').css({'pointer-events': 'auto', 'background-color': '#fff'}).removeAttr('tabindex');

    // PERBAIKAN: Pastikan dropdown tipe dibersihkan dan diaktifkan (buang disabled)
    $('#select-tipe').empty().append('<option value="">-- Pilih Tipe --</option>')
                    .removeAttr('disabled')
                    .css({'pointer-events': 'auto', 'background-color': '#fff'}).removeAttr('tabindex');
}

// ==========================================
// 5. LOGIKA JASA & SPAREPART
// ==========================================

function bukaMasterJasa() {
    $('#searchJasa').val('');
    $('.filter-jasa-btn').removeClass('active btn-dark text-white').addClass('btn-outline-dark');
    $('.filter-jasa-btn[data-filter="all"]').addClass('active btn-dark text-white').removeClass('btn-outline-dark');
    filterKategoriJasa('all');
    new bootstrap.Modal(document.getElementById('modalMasterJasa')).show();
}

function bukaMasterPart() {
    $('#searchPart').val('');
    $('.item-baris-part').show();
    new bootstrap.Modal(document.getElementById('modalMasterPart')).show();
}

function filterKategoriJasa(kategori, btn = null) {
    if(btn) {
        $('.filter-jasa-btn').removeClass('active btn-dark text-white').addClass('btn-outline-dark');
        $(btn).addClass('active btn-dark text-white').removeClass('btn-outline-dark');
    }
    let search = $('#searchJasa').val().toLowerCase();
    let aktifCat = $('.filter-jasa-btn.active').data('filter').toLowerCase();

    $('.item-baris-jasa').each(function() {
        let rowCat = $(this).data('kategori').toString().toLowerCase();
        let rowNama = $(this).data('nama').toString().toLowerCase();
        let matchKategori = (aktifCat === 'all' || rowCat === aktifCat);
        $(this).toggle(matchKategori && rowNama.includes(search));
    });
}

function pilihJasaKeList(id, nama, harga) {
    let sudahAda = false;
    $('input[name="jasa_id[]"]').each(function() { if ($(this).val() == id) sudahAda = true; });
    if (sudahAda) return alert('Jasa sudah ada!');

    let html = `<div class="card p-2 mb-2 border-0 shadow-sm item-jasa bg-white border-start border-success border-4">
        <div class="d-flex justify-content-between align-items-center">
            <div style="flex: 1;">
                <input type="hidden" name="jasa_id[]" value="${id}">
                <h6 class="mb-0 fw-bold small text-dark text-uppercase">${nama}</h6>
                <small class="text-primary fw-bold">Rp ${parseInt(harga).toLocaleString('id-ID')}</small>
                <input type="hidden" class="harga-jasa-hidden" value="${harga}">
            </div>
            <a href="javascript:void(0)" class="text-danger ms-3" onclick="hapusElemen(this)"><i class="bi bi-trash"></i></a>
        </div>
    </div>`;
    $('#wrapper-jasa-terpilih').append(html);
    bootstrap.Modal.getInstance(document.getElementById('modalMasterJasa'))?.hide();
    hitungTotal();
}

function pilihDariMaster(id, nama, harga, stok) {
    let html = `<div class="card p-2 mb-2 border-0 shadow-sm item-part bg-white border-start border-primary border-4">
        <div class="d-flex justify-content-between align-items-center">
            <div style="flex: 1;">
                <input type="hidden" name="sparepart_id[]" value="${id}">
                <h6 class="mb-0 fw-bold small text-dark">${nama}</h6>
                <small class="text-primary fw-bold">Rp ${parseInt(harga).toLocaleString('id-ID')}</small>
                <div class="d-none data-bantuan" data-harga="${harga}"></div>
            </div>
            <div class="d-flex align-items-center">
                <input type="number" name="qty[]" class="form-control form-control-sm qty-part text-center" value="1" min="1" max="${stok}" style="width: 55px;" onchange="hitungTotal()">
                <a href="javascript:void(0)" class="text-danger ms-3" onclick="hapusElemen(this)"><i class="bi bi-trash"></i></a>
            </div>
        </div>
    </div>`;
    $('#wrapper-part').append(html);
    bootstrap.Modal.getInstance(document.getElementById('modalMasterPart'))?.hide();
    hitungTotal();
}

function hapusElemen(el) {
    $(el).closest('.item-jasa, .item-part').remove();
    hitungTotal();
}

// ==========================================
// 6. PERHITUNGAN & PEMBAYARAN
// ==========================================

function hitungTotal() {
    let total = 0;
    $('.item-jasa').each(function() { total += parseFloat($(this).find('.harga-jasa-hidden').val() || 0); });
    $('.item-part').each(function() {
        let h = parseFloat($(this).find('.data-bantuan').attr('data-harga') || 0);
        let q = parseInt($(this).find('.qty-part').val() || 0);
        total += (h * q);
    });
    $('#label-total-bawah, #modal-label-total').text(total.toLocaleString('id-ID'));
    $('#input-total-hidden').val(total);
    hitungKembalian();
}

function hitungKembalian() {
    let total = parseInt($('#input-total-hidden').val()) || 0;
    let bayar = parseInt($('#input-bayar').val()) || 0;
    let kembali = bayar - total;
    $('#label-kembali').text(kembali >= 0 ? kembali.toLocaleString('id-ID') : "0");
    $('#pesan-error').toggleClass('d-none', bayar >= total);
    $('#btn-submit-form').prop('disabled', (bayar < total || total <= 0));
}

function setUangPas() {
    $('#input-bayar').val($('#input-total-hidden').val());
    hitungKembalian();
}

function validasiDanBukaModal() {
    const total = parseInt($('#input-total-hidden').val()) || 0;
    if (total <= 0) return alert("Keranjang belanja kosong!");
    new bootstrap.Modal(document.getElementById('modalBayar')).show();
}

// Fungsi yang dicari oleh Error Console Anda
function disableButton() {
    const btn = document.getElementById('btn-submit-form');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
    }
}

// FUNGSI MODAL TAMBAH UNIT

function bukaModalTambahUnit() {
    // Ambil merk yang terpilih di dropdown utama form servis
    let currentMerk = $('#select-merk').val();

    if(currentMerk) {
        // Set dropdown di dalam modal agar sama dengan dropdown utama
        $('#new_merk').val(currentMerk);
    } else {
        // Reset jika tidak ada yang terpilih
        $('#new_merk').val('');
    }

    new bootstrap.Modal(document.getElementById('modalTambahUnit')).show();
}

function simpanUnitBaru() {
    let namaUnit = $('#new_nama_unit').val();
    let merkUnit = $('#new_merk').val();

    if (!namaUnit || !merkUnit) {
        Swal.fire('Gagal', 'Merk dan Nama Unit harus diisi!', 'error');
        return;
    }

    Swal.fire({
        title: 'Menyimpan...',
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: "{{ route('units.storeAjax') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            nama_unit: namaUnit,
            merk: merkUnit
        },
        success: function(response) {
            if (response.success) {
                $('#modalTambahUnit').modal('hide');
                $('#new_nama_unit').val('');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Unit berhasil didaftarkan.',
                    timer: 1500,
                    showConfirmButton: false
                });

                // 1. Set Merk di form utama
                $('#select-merk').val(response.data.merk).trigger('change');

                // 2. Tunggu sebentar sampai ajax list motor selesai, lalu pilih unitnya
                setTimeout(function(){
                    $('#select-unit').val(response.data.id).trigger('change');
                }, 1000);
            }
        },
        error: function() {
            Swal.fire('Error', 'Gagal menyimpan data ke database.', 'error');
        }
    });

}

//RESPONSIF TOOGLE SIDEBAR

function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('show');
    document.querySelector('.sidebar-overlay').classList.toggle('show');
}

/* tombol toggle hide saat modal buka */
document.addEventListener('DOMContentLoaded', function () {

    const toggleBtn = document.querySelector('.mobile-toggle');

    document.querySelectorAll('.modal').forEach(function(modal){

        modal.addEventListener('shown.bs.modal', function () {
            if(toggleBtn){
                toggleBtn.style.display = 'none';
            }

            // tutup sidebar kalau masih kebuka
            document.getElementById('sidebar')?.classList.remove('show');
            document.querySelector('.sidebar-overlay')?.classList.remove('show');
        });

        modal.addEventListener('hidden.bs.modal', function () {
            if(toggleBtn && window.innerWidth <= 768){
                toggleBtn.style.display = 'flex';
            }
        });

    });

});


</script>
@endsection
