@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 top-header-mobile">
        <h1 class="h3 mb-0 text-gray-800">Laporan Keuangan</h1>

        <div class="top-action-group">
            <a href="{{ route('laporan.keuangan.pdf', request()->all()) }}"
               id="btn-pdf"
               class="btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Cetak PDF
            </a>

            <a href="#"
               id="btn-wa"
               target="_blank"
               class="btn btn-sm btn-success shadow-sm">
                <i class="fab fa-whatsapp fa-sm text-white-50"></i> Kirim WA
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="filter-form" class="form-inline">
                <div class="form-group mr-3">
                    <label class="mr-2 font-weight-bold small">Cari:</label>
                    <input type="text"
                           name="search"
                           id="search-keyword"
                           class="form-control form-control-sm"
                           placeholder="No. Faktur...">
                </div>

                <div class="form-group mr-3">
                    <label class="mr-2 font-weight-bold small">Periode:</label>
                    <select name="filter"
                            id="filter-cepat"
                            class="form-control form-control-sm">
                        <option value="hari_ini">Hari Ini</option>
                        <option value="minggu_ini" selected>Minggu Ini</option>
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="custom">Rentang Tanggal</option>
                    </select>
                </div>

                <div id="range-tanggal-container"
                     class="form-group mr-3 d-none">
                    <input type="date"
                           name="tgl_mulai"
                           id="tgl_mulai"
                           class="form-control form-control-sm mr-2">

                    <input type="date"
                           name="tgl_selesai"
                           id="tgl_selesai"
                           class="form-control form-control-sm">
                </div>

                <div class="form-group">
                    <label class="mr-2 font-weight-bold small">Sort Profit:</label>
                    <select name="sort"
                            id="sort-profit"
                            class="form-control form-control-sm">
                        <option value="desc">Tertinggi</option>
                        <option value="asc">Terendah</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Transaksi
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive finance-scroll">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No</th>
                            <th>No. Faktur</th>
                            <th>Tanggal</th>
                            <th>Jual Part</th>
                            <th>Total Servis</th>
                            <th class="bg-warning text-dark">Omzet Kotor</th>
                            <th>Margin Part</th>
                            <th class="bg-success text-white">Profit</th>
                        </tr>
                    </thead>

                    <tbody id="data-container"></tbody>

                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="3" class="text-center">
                                TOTAL SUMMARY
                            </td>

                            <td class="text-right" id="total-part">
                                Rp {{ number_format($summary['part'] ?? 0,0,',','.') }}
                            </td>

                            <td class="text-right" id="total-servis">
                                Rp {{ number_format($summary['servis'] ?? 0,0,',','.') }}
                            </td>

                            <td class="text-right bg-warning" id="total-kotor">
                                Rp {{ number_format($summary['kotor'] ?? 0,0,',','.') }}
                            </td>

                            <td class="text-right text-info" id="total-margin">
                                Rp {{ number_format($summary['margin'] ?? 0,0,',','.') }}
                            </td>

                            <td class="text-right text-white bg-success" id="total-bersih">
                                Rp {{ number_format($summary['bersih'] ?? 0,0,',','.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div id="loading-state" class="text-center d-none py-3">
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="small ml-2">Memuat data...</span>
            </div>
        </div>
    </div>
</div>

{{-- CSS RESPONSIVE --}}
<style>
/* DESKTOP */
.finance-scroll{
    overflow-x:auto;
}

.finance-scroll table{
    min-width:1000px;
}

/* MOBILE */
@media (max-width:768px){

    .container-fluid{
        padding-left:10px !important;
        padding-right:10px !important;
    }

    .top-header-mobile{
        display:block !important;
    }

    .top-header-mobile h1{
        font-size:22px !important;
        margin-bottom:12px !important;
    }

    .top-action-group .btn{
        width:100%;
        margin-bottom:8px;
        min-height:44px;
        border-radius:10px;
    }

    .card{
        border-radius:14px !important;
    }

    .card-body{
        padding:14px !important;
    }

    /* FORM */
    #filter-form{
        display:block !important;
    }

    #filter-form .form-group{
        display:block !important;
        width:100%;
        margin-right:0 !important;
        margin-bottom:12px !important;
    }

    #filter-form label{
        display:block;
        margin-bottom:6px;
        font-size:12px;
    }

    #filter-form .form-control{
        width:100% !important;
        height:46px;
        font-size:14px;
        border-radius:10px;
    }

    #range-tanggal-container input{
        margin-bottom:8px;
        margin-right:0 !important;
    }

    /* TABLE */
    .finance-scroll{
        overflow-x:auto !important;
        overflow-y:hidden !important;
        -webkit-overflow-scrolling:touch;
        border-radius:12px;
    }

    .finance-scroll table{
        min-width:1050px !important;
    }

    .finance-scroll th,
    .finance-scroll td{
        white-space:nowrap !important;
        font-size:13px !important;
        padding:10px !important;
        vertical-align:middle !important;
    }

    .finance-scroll thead th{
        font-size:11px !important;
        text-transform:uppercase;
    }

    .finance-scroll::after{
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    let page = 1;
    let hasMoreData = true;
    let loading = false;

    loadData(true);

    function loadData(reset = false){

        if(loading) return;

        if(reset){
            page = 1;
            hasMoreData = true;
            $('#data-container').empty();
        }

        if(!hasMoreData) return;

        loading = true;
        $('#loading-state').removeClass('d-none');

        $.ajax({
            url: "{{ route('laporan.keuangan') }}",
            type: "GET",
            data: $('#filter-form').serialize() + '&page=' + page,

            success: function(res){

                if(res.html.trim() === ''){
                    hasMoreData = false;
                }else{
                    $('#data-container').append(res.html);
                    hasMoreData = res.hasMore;
                }

                if(res.summary){
                    $('#total-part').text('Rp ' + res.summary.part);
                    $('#total-servis').text('Rp ' + res.summary.servis);
                    $('#total-kotor').text('Rp ' + res.summary.kotor);
                    $('#total-margin').text('Rp ' + res.summary.margin);
                    $('#total-bersih').text('Rp ' + res.summary.bersih);

                    updateWaLink(res.summary);
                }

                loading = false;
                $('#loading-state').addClass('d-none');
            },

            error:function(){
                loading = false;
                $('#loading-state').addClass('d-none');
            }
        });
    }

    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 150){
            if(hasMoreData && !loading){
                page++;
                loadData(false);
            }
        }
    });

    $('#filter-cepat').on('change', function(){

        if($(this).val() === 'custom'){
            $('#range-tanggal-container').removeClass('d-none');
        }else{
            $('#range-tanggal-container').addClass('d-none');
            loadData(true);
        }
    });

    $('#search-keyword, #sort-profit, #tgl_mulai, #tgl_selesai')
    .on('keyup change', function(){

        clearTimeout(window.timer);

        window.timer = setTimeout(function(){
            loadData(true);
        },500);
    });

});

function updateWaLink(summary){

    let phone   = "6282240247129";
    let periode = $('#filter-cepat option:selected').text();
    let search  = $('#search-keyword').val();

    let text  = `*LAPORAN KEUANGAN BENGKEL*%0A`;
    text += `Periode: *${periode}*%0A`;

    if(search){
        text += `Filter No. Faktur: ${search}%0A`;
    }

    text += `%0A--------------------%0A`;
    text += `Part: Rp ${summary.part}%0A`;
    text += `Servis: Rp ${summary.servis}%0A`;
    text += `*Omzet: Rp ${summary.kotor}*%0A`;
    text += `Margin: Rp ${summary.margin}%0A`;
    text += `*Profit: Rp ${summary.bersih}*%0A`;

    $('#btn-wa').attr(
        'href',
        `https://wa.me/${phone}?text=${text}`
    );
}
</script>

@endsection
