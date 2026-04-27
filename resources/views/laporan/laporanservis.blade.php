@extends('layouts.app')

@section('content')

<style>
/* =====================================
RESPONSIVE MOBILE - LAPORAN SERVIS
===================================== */
@media (max-width:768px){

    .container-fluid{
        padding-left:10px !important;
        padding-right:10px !important;
    }

    /* TAB MENU */
    .nav-tabs{
        display:flex;
        flex-wrap:nowrap;
        overflow-x:auto;
        white-space:nowrap;
        -webkit-overflow-scrolling:touch;
    }

    .nav-tabs .nav-link{
        font-size:13px;
        padding:10px 14px;
    }

    /* HEADER */
    h1.h3{
        font-size:22px !important;
        margin-bottom:14px !important;
    }

    /* CARD */
    .card{
        border-radius:14px !important;
        margin-bottom:14px !important;
    }

    .card-body{
        padding:14px !important;
    }

    .card-header{
        padding:12px 14px !important;
    }

    /* FILTER */
    form.row > div{
        margin-bottom:12px;
    }

    label{
        font-size:12px;
        margin-bottom:6px;
    }

    .form-control{
        height:46px !important;
        font-size:14px !important;
        border-radius:10px !important;
    }

    .btn{
        min-height:46px;
        border-radius:10px !important;
    }

    /* SUMMARY BOX */
    .h5{
        font-size:18px !important;
    }

    .text-xs{
        font-size:11px !important;
        line-height:1.4;
    }

    /* TABLE */
    .table-responsive{
        overflow-x:auto !important;
        overflow-y:hidden !important;
        -webkit-overflow-scrolling:touch;
        border-radius:12px;
    }

    .table{
        min-width:980px !important;
        margin-bottom:0 !important;
    }

    .table th,
    .table td{
        white-space:nowrap;
        font-size:13px !important;
        padding:10px !important;
        vertical-align:top !important;
    }

    .table thead th{
        font-size:11px !important;
        text-transform:uppercase;
    }

    /* Kolom khusus */
    .table th:nth-child(1),
    .table td:nth-child(1){
        min-width:150px;
    }

    .table th:nth-child(2),
    .table td:nth-child(2){
        min-width:220px;
    }

    .table th:nth-child(3),
    .table td:nth-child(3){
        min-width:140px;
    }

    .table th:nth-child(4),
    .table td:nth-child(4){
        min-width:320px;
        white-space:normal !important;
    }

    .table th:nth-child(5),
    .table td:nth-child(5){
        min-width:160px;
        text-align:right !important;
    }

    /* badge polisi */
    .badge{
        font-size:11px;
        padding:6px 8px;
    }

    /* info swipe */
    .table-responsive::after{
        content:"Geser tabel ke samping ← →";
        display:block;
        text-align:center;
        font-size:11px;
        color:#6c757d;
        padding:8px;
        border-top:1px solid #eee;
        background:#fafafa;
    }
}
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Riwayat Servis</h1>

    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Periode Waktu</label>
                    <select name="filter" id="filterPeriode" class="form-control" onchange="toggleCustomDate()">
                        <option value="hari_ini" {{ request('filter') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="minggu_ini" {{ request('filter') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ request('filter') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun_ini" {{ request('filter') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Rentang Tanggal Kustom</option>
                    </select>
                </div>
                <div class="col-md-3 {{ request('filter') == 'custom' ? '' : 'd-none' }}" id="div_mulai">
                    <label class="form-label font-weight-bold">Dari</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                </div>
                <div class="col-md-3 {{ request('filter') == 'custom' ? '' : 'd-none' }}" id="div_selesai">
                    <label class="form-label font-weight-bold">Sampai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kendaraan Servis</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_unit'] }} Unit</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan Jasa</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($summary['total_jasa'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Part Terpasang (Servis)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($summary['total_part'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Omzet Servis</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($summary['total_omzet'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>



    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white text-dark">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-tools mr-2"></i> Detail Transaksi Servis</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Tgl / Faktur</th>
                            <th>Konsumen & Unit</th>
                            <th>Mekanik</th>
                            <th>Rincian Jasa & Part</th>
                            <th>Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $row)
                        <tr>
                            <td>
                                <strong>{{ $row->created_at->format('d/m/Y') }}</strong><br>
                                <small class="text-primary">{{ $row->no_faktur }}</small>
                            </td>
                            <td>
                                <strong>{{ $row->konsumen?->nama_konsumen ?? 'Pelanggan Umum' }}</strong><br>
                                <span class="badge badge-dark">{{ $row->no_polisi }}</span>
                                <small>{{ $row->kendaraan?->merk_tipe ?? '' }}</small>
                            </td>
                            <td>{{ $row->mekanik?->nama_mekanik ?? '-' }}</td>
                            <td>
                                <ul class="list-unstyled mb-0" style="font-size: 0.85rem;">
                                    @foreach($row->details as $item)
                                    <li>
                                        @if($item->tipe == 'jasa')
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                        @else
                                            <i class="fas fa-cog text-muted mr-1"></i>
                                        @endif
                                        {{ $item->nama_item }} ({{ $item->qty }})
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-right font-weight-bold">
                                Rp {{ number_format($row->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Tidak ada data transaksi servis pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
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



</script>
@endsection
