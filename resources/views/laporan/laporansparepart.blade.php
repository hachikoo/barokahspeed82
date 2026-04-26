@extends('layouts.app')

@section('content')

<style>
/* ===============================
BASE
=============================== */
.collapse{display:none;}
.collapse.show{display:block;}

#modalDetail.show{
    display:block !important;
    background:rgba(0,0,0,.6);
    z-index:9999;
}

/* sembunyikan toggle sidebar */
body.modal-detail-open .sidebar-toggle,
body.modal-detail-open #sidebarToggle,
body.modal-detail-open .navbar-toggler{
    display:none !important;
}

/* ===============================
DESKTOP MODAL
=============================== */
#modalDetail .modal-dialog{
    width:96%;
    max-width:1100px;
}

#modalDetail .modal-body{
    max-height:80vh;
    overflow:auto;
}

/* ===============================
MOBILE
=============================== */
@media (max-width:768px){

    .container-fluid{
        padding-left:10px !important;
        padding-right:10px !important;
    }

    /* JANGAN global ke semua table */
    .card-body > .table-responsive{
        overflow-x:auto;
        -webkit-overflow-scrolling:touch;
    }

    .card-body > .table-responsive > table{
        min-width:760px;
    }

    /* tabel rincian faktur */
    #targetRincian .table-responsive{
        overflow-x:auto !important;
    }

    #targetRincian .table{
        min-width:780px !important;
    }

    #targetRincian th,
    #targetRincian td{
        font-size:12px !important;
        padding:8px !important;
        white-space:nowrap;
    }

    #targetRincian td:nth-child(2){
        white-space:normal !important;
        min-width:180px;
    }

    /* tabel kecil dalam kolom item / qty */
    #targetRincian td table{
        min-width:auto !important;
        width:100% !important;
    }

    #targetRincian td table td{
        padding:6px !important;
        white-space:normal !important;
        border:none !important;
    }

    /* ==========================
       MODAL FULLSCREEN
    ========================== */
    #modalDetail{
        padding:0 !important;
    }

    #modalDetail .modal-dialog{
        margin:0 !important;
        width:100% !important;
        max-width:100% !important;
        height:100vh;
    }

    #modalDetail .modal-content{
        height:100vh;
        border-radius:0;
        display:flex;
        flex-direction:column;
    }

    #modalDetail .modal-body{
        flex:1;
        overflow-y:auto !important;
        overflow-x:auto !important;
        padding:10px !important;
        -webkit-overflow-scrolling:touch;
    }

    /* WRAPPER MODAL */
    #modalDetail .modal-scroll-table{
        width:100%;
        overflow-x:auto !important;
        overflow-y:hidden !important;
        -webkit-overflow-scrolling:touch;
        border:1px solid #ddd;
        background:#fff;
    }

    /* hanya tabel modal */
    #modalDetail .modal-wide-table{
        min-width:820px !important;
        width:820px !important;
        max-width:none !important;
    }

    #modalDetail .modal-wide-table th,
    #modalDetail .modal-wide-table td{
        font-size:12px !important;
        padding:8px !important;
        white-space:nowrap;
    }

    #modalDetail .modal-wide-table th:first-child,
    #modalDetail .modal-wide-table td:first-child{
        min-width:260px;
        white-space:normal !important;
    }

    #modalDetail .modal-scroll-table::after{
        content:"Geser tabel kiri / kanan";
        display:block;
        text-align:center;
        font-size:11px;
        padding:7px;
        color:#666;
    }
}
</style>

</style>
<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link {{ request()->is('laporan/servis') ? 'active' : '' }}" href="{{ route('laporan.index') }}">Riwayat Servis</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ request()->is('laporan/sparepart') ? 'active' : '' }}" href="{{ route('laporan.sparepart') }}">Penjualan Sparepart</a>
  </li>
</ul>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan Sparepart</h1>

    <div class="card shadow mb-4 border-0">
        <div class="card-body">
            <form action="{{ route('laporan.sparepart') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">Periode</label>
                    <select name="filter" id="filterPeriode" class="form-control" onchange="toggleCustomDate()">
                        <option value="bulan_ini" {{ request('filter') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="minggu_ini" {{ request('filter') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Rentang Tanggal</option>
                    </select>
                </div>
                <div class="col-md-3 {{ request('filter') == 'custom' ? '' : 'd-none' }}" id="div_mulai">
                    <label class="small font-weight-bold">Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                </div>
                <div class="col-md-3 {{ request('filter') == 'custom' ? '' : 'd-none' }}" id="div_selesai">
                    <label class="small font-weight-bold">Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sync mr-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="small text-white-50">Total Barang Terjual</div>
                    <div class="h3 font-weight-bold">{{ number_format($summary['total_pcs'], 0, ',', '.') }} <span class="small">Pcs</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="small text-white-50">Total Omzet Penjualan</div>
                    <div class="h3 font-weight-bold">Rp {{ number_format($summary['total_omzet'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="small text-white-50">Total Margin (Keuntungan)</div>
                    <div class="h3 font-weight-bold">Rp {{ number_format($summary['total_margin'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi Item Terjual</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th class="text-center">Total Pcs</th>
                            <th class="text-right">Total Omzet (Jual)</th>
                            <th class="text-right">Total Margin (Profit)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapSparepart as $item)
                        <tr>
                            <td><strong>{{ $item['nama_item'] }}</strong></td>
                            <td class="text-center">{{ $item['total_qty'] }}</td>
                            <td class="text-right">Rp {{ number_format($item['total_omzet'], 0, ',', '.') }}</td>
                            <td class="text-right text-success font-weight-bold">
                                Rp {{ number_format($item['total_margin'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

 <div class="card shadow mb-4 border-0">
    <div class="card-header py-3 bg-dark text-white d-flex justify-content-between align-items-center"
         onclick="toggleRincian()"
         style="cursor: pointer;">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-file-invoice-dollar mr-2"></i> Rincian Penjualan Per Faktur
        </h6>
        <i class="fas fa-chevron-down" id="iconToggle"></i>
    </div>

    <div id="targetRincian" class="collapse show">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead class="thead-light text-center small font-weight-bold">
                        <tr>
                            <th width="15%">No. Faktur & Tanggal</th>
                            <th width="35%">Item Sparepart</th>
                            <th width="8%">Qty</th>
                            <th width="15%">Total Omzet</th>
                            <th width="15%">Total Margin</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $t)
                        @php
                            $detailsPart = $t->details->where('tipe', 'sparepart');
                            $fakturOmzet = $detailsPart->sum('subtotal');
                            $fakturMargin = $detailsPart->sum(function($d) {
                                $beli = $d->sparepart->harga_beli ?? 0;
                                return ($d->harga_satuan - $beli) * $d->qty;
                            });
                        @endphp
                        <tr>
                            <td class="text-center align-middle">
                                <span class="badge badge-primary p-2 mb-1 d-block"
                                      style="color: #000000 !important; font-size: 0.85rem; font-weight: bold; border-radius: 5px;">
                                    {{ $t->no_faktur }}
                                </span>
                                <small class="text-muted">{{ $t->created_at->format('d/m/Y') }}</small>
                            </td>

                            <td class="p-0">
                                <table class="table table-borderless mb-0">
                                    @foreach($detailsPart as $d)
                                    <tr class="{{ !$loop->last ? 'border-bottom' : '' }}">
                                        <td class="py-2">{{ $d->nama_item }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>

                            <td class="p-0 text-center align-middle">
                                <table class="table table-borderless mb-0">
                                    @foreach($detailsPart as $d)
                                    <tr class="{{ !$loop->last ? 'border-bottom' : '' }}">
                                        <td class="py-2 font-weight-bold">{{ $d->qty }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>

                            <td class="text-right align-middle font-weight-bold text-dark">
                                Rp {{ number_format($fakturOmzet, 0, ',', '.') }}
                            </td>
                            <td class="text-right align-middle text-success font-weight-bold">
                                Rp {{ number_format($fakturMargin, 0, ',', '.') }}
                            </td>

                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-sm btn-info shadow-sm" onclick="showDetail('{{ $t->no_faktur }}')">
                                    <i class="fas fa-eye"></i> Detail
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

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Rincian Transaksi: <span id="modalNoFaktur"></span></h5>
                <button type="button" class="close text-white" onclick="closeModal()">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBodyContent" style="max-height: 70vh; overflow-y: auto;">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleHeader = document.querySelector('[data-toggle="collapse"]');
    const collapseTarget = document.getElementById('collapseDetail');
    if (toggleHeader && collapseTarget) {
        toggleHeader.addEventListener('click', function() {
            collapseTarget.classList.toggle('show');
        });
    }
});
function showDetail(noFaktur)
{
    const modal = document.getElementById('modalDetail');
    const body  = document.getElementById('modalBodyContent');

    document.getElementById('modalNoFaktur').innerText = noFaktur;

    modal.style.display = 'block';
    modal.classList.add('show');

    document.body.classList.add('modal-open');
    document.body.classList.add('modal-detail-open');
    document.body.style.overflow = 'hidden';

    body.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border spinner-border-sm"></div>
            <div class="mt-2 small text-muted">Memuat detail...</div>
        </div>
    `;

    fetch('/laporan/detail-transaksi/' + noFaktur)
    .then(res => res.text())
    .then(html => {
        body.innerHTML = html;
    })
    .catch(() => {
        body.innerHTML =
        '<div class="alert alert-danger">Gagal memuat detail transaksi.</div>';
    });
}

function closeModal()
{
    const modal = document.getElementById('modalDetail');

    modal.classList.remove('show');
    modal.style.display = 'none';

    document.body.classList.remove('modal-open');
    document.body.classList.remove('modal-detail-open');
    document.body.style.overflow = '';
}



function toggleCustomDate() {
    const filter = document.getElementById('filterPeriode').value;
    const divMulai = document.getElementById('div_mulai');
    const divSelesai = document.getElementById('div_selesai');

    if (filter === 'custom') {
        divMulai.classList.remove('d-none');
        divSelesai.classList.remove('d-none');
    } else {
        divMulai.classList.add('d-none');
        divSelesai.classList.add('d-none');
    }
}

// Fungsi Toggle untuk Rincian Per Faktur
function toggleRincian() {
    const target = document.getElementById('targetRincian');
    const icon = document.getElementById('iconToggle');

    if (target.classList.contains('show')) {
        target.classList.remove('show');
        target.style.display = 'none';
        icon.classList.replace('fa-chevron-down', 'fa-chevron-right');
    } else {
        target.classList.add('show');
        target.style.display = 'block';
        icon.classList.replace('fa-chevron-right', 'fa-chevron-down');
    }
}
</script>


@endsection
