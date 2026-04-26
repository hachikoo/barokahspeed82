<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Barokah Speed | Warehouse System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0d6efd">

<!-- iOS support -->
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<style>
body{
    background:#f8f9fa;
    font-family:'Inter', -apple-system, sans-serif;
    overflow-x:hidden;
}

/* ===============================
   DESKTOP
=================================*/
#wrapper{
    display:flex;
    min-height:100vh;
}

#sidebar{
    width:280px;
    min-width:280px;
    background:#111418;
    color:#fff;
    min-height:100vh;
    transition:.3s ease;
    box-shadow:4px 0 10px rgba(0,0,0,.1);
}

#content{
    flex:1;
    padding:24px;
    background:#f8f9fa;
}

/* ===============================
   SIDEBAR STYLE
=================================*/
.sidebar-header{
    padding:30px 20px;
    text-align:center;
}

.brand-logo{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    margin-bottom:5px;
}

.sidebar-heading{
    font-size:.72rem;
    text-transform:uppercase;
    letter-spacing:1.5px;
    color:#6c757d;
    padding:18px 25px 10px;
    font-weight:700;
}

#sidebar ul li{
    list-style:none;
}

#sidebar ul{
    padding:0;
    margin:0;
}

#sidebar ul li a{
    padding:12px 25px;
    display:flex;
    align-items:center;
    gap:10px;
    color:#a0a0a0;
    text-decoration:none;
    font-size:.92rem;
    border-left:4px solid transparent;
    transition:.2s;
}

#sidebar ul li a:hover{
    color:#fff;
    background:rgba(255,255,255,.05);
    border-left:4px solid #0d6efd;
}

#sidebar ul li.active > a{
    color:#fff;
    background:rgba(13,110,253,.15);
    border-left:4px solid #0d6efd;
    font-weight:600;
}

.nav-special{
    margin:15px 20px;
    padding:12px !important;
    background:#0d6efd;
    color:#fff !important;
    border-radius:8px;
    justify-content:center;
    box-shadow:0 4px 12px rgba(13,110,253,.3);
}

.nav-special:hover{
    background:#0b5ed7 !important;
}

/* ===============================
   MOBILE
=================================*/
.mobile-toggle{
    display:none;
}

.sidebar-overlay{
    display:none;
}

@media (max-width:768px){

    #wrapper{
        display:block;
    }

    #sidebar{
        position:fixed;
        top:0;
        left:-280px;
        z-index:1055;
        height:100vh;
        overflow-y:auto;
    }

    #sidebar.show{
        left:0;
    }

    #content{
        width:100%;
        padding:65px 14px 14px 14px !important;
    }

    .mobile-toggle{
        display:flex;
        align-items:center;
        justify-content:center;
        position:fixed;
        top:12px;
        left:12px;
        width:42px;
        height:42px;
        border:none;
        border-radius:10px;
        background:#0d6efd;
        color:#fff;
        font-size:20px;
        z-index:1100;
        box-shadow:0 4px 10px rgba(0,0,0,.2);
    }

    .sidebar-overlay.show{
        display:block;
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.45);
        z-index:1050;
    }
}

/* ==================================
   APP BOTTOM NAV (GOJEK STYLE)
================================== */
@media (max-width:768px){

    .app-bottom-nav{
        position:fixed;
        bottom:0;
        left:0;
        width:100%;
        height:70px;
        background:#fff;
        display:flex;
        justify-content:space-around;
        align-items:center;
        border-top:1px solid #eee;
        z-index:1100;
        box-shadow:0 -4px 12px rgba(0,0,0,0.06);
    }

    .app-bottom-nav .nav-item{
        flex:1;
        text-align:center;
        text-decoration:none;
        color:#6c757d;
        font-size:10px;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        gap:3px;
        transition:.2s;
    }

    .app-bottom-nav .nav-item i{
        font-size:20px;
    }

    .app-bottom-nav .nav-item.active{
        color:#0d6efd;
        font-weight:600;
    }

    /* FAB (Transaksi Baru) */
    .app-bottom-nav .fab{
        position:relative;
        top:-22px;
        background:#0d6efd;
        color:#fff !important;
        width:58px;
        height:58px;
        border-radius:50%;
        display:flex !important;
        align-items:center;
        justify-content:center;
        box-shadow:0 6px 16px rgba(0,0,0,0.25);
    }

    .app-bottom-nav .fab i{
        font-size:24px;
    }

    /* biar konten ga ketutup */
    #content{
        padding-bottom:95px !important;
    }



}

@media (max-width:768px){

    .mobile-toggle{
        display:none !important;
    }

    #sidebar{
        display:none;
    }

    .sidebar-overlay{
        display:none !important;
    }

}
.app-bottom-nav .nav-item:active{
    transform:scale(0.9);
}

@media (max-width:768px){
    #content{
        padding-top:14px !important;
    }
}

/* LOCK LANDSCAPE */
#rotate-lock{
    display:none;
}

@media screen and (orientation: landscape) and (max-width: 900px){
    #rotate-lock{
        display:flex;
        position:fixed;
        inset:0;
        background:#111;
        color:#fff;
        z-index:99999;
        align-items:center;
        justify-content:center;
        text-align:center;
    }

    .rotate-box i{
        font-size:50px;
        margin-bottom:10px;
    }

    .rotate-box p{
        font-size:16px;
    }

    /* sembunyikan semua UI */
    #wrapper,
    .app-bottom-nav,
    .mobile-toggle{
        display:none !important;
    }
}
.rotate-box{
    animation:fadeIn .3s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:scale(0.9);}
    to{opacity:1; transform:scale(1);}
}
</style>
</head>

<body>

<button class="mobile-toggle" onclick="toggleSidebar()">☰</button>
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<div id="wrapper">

    <nav id="sidebar">

        <div class="sidebar-header">
            <div class="brand-logo">
                <i class="bi bi-8-square-fill fs-3 text-primary"></i>
                <i class="bi bi-activity fs-4 text-white"></i>
                <i class="bi bi-2-square-fill fs-3 text-primary"></i>
            </div>

            <h5 class="fw-bold mb-0">
                Barokah <span class="text-primary">Speed</span>
            </h5>

            <small class="text-muted">WAREHOUSE SYSTEM</small>
        </div>

        <ul>

            <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                <a href="/dashboard">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>

            <div class="sidebar-heading">Master Data</div>

            <li class="{{ Request::is('master/sparepart*') ? 'active' : '' }}">
                <a href="{{ route('sparepart.index') }}">
                    <i class="bi bi-box-seam-fill"></i> Data Sparepart
                </a>
            </li>

            <li class="{{ Request::is('master/konsumen*') ? 'active' : '' }}">
                <a href="{{ route('konsumen.index') }}">
                    <i class="bi bi-people-fill"></i> Data Konsumen
                </a>
            </li>

            <li class="{{ Request::is('master/unit*') ? 'active' : '' }}">
                <a href="{{ route('unit.index') }}">
                    <i class="bi bi-bicycle"></i> Data Tipe Unit Motor
                </a>
            </li>

            <li class="{{ Request::is('master/jasa*') ? 'active' : '' }}">
                <a href="{{ route('jasa.index') }}">
                    <i class="bi bi-tools"></i> Data Jasa
                </a>
            </li>

            <li class="{{ Request::is('master/mekanik*') ? 'active' : '' }}">
                <a href="{{ route('mekanik.index') }}">
                    <i class="bi bi-person-badge-fill"></i> Data Mekanik
                </a>
            </li>

            <div class="sidebar-heading">Transaksi</div>

            <li>
                <a href="{{ route('transaksi.baru') }}" class="nav-special">
                    <i class="bi bi-plus-circle-fill"></i> Transaksi Baru
                </a>
            </li>

            <div class="sidebar-heading">Laporan</div>

            <li class="{{ request()->is('laporan/keuangan*') ? 'active' : '' }}">
                <a href="{{ route('laporan.keuangan') }}">
                    <i class="bi bi-cash-stack"></i> Laporan Keuangan
                </a>
            </li>

            <li class="{{ request()->is('laporan/sparepart') ? 'active' : '' }}">
                <a href="{{ route('laporan.sparepart') }}">
                    <i class="bi bi-boxes"></i> Servis & Penjualan Sparepart
                </a>
            </li>

        </ul>
    </nav>

    <div id="content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('show');
    document.querySelector('.sidebar-overlay').classList.toggle('show');
}
</script>


<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(() => console.log('Service Worker Registered'));
}
</script>

<!-- MOBILE APP NAV -->
<div class="app-bottom-nav d-md-none">

    <a href="/dashboard" class="nav-item {{ Request::is('dashboard*') ? 'active' : '' }}">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
    </a>

    <a href="{{ route('sparepart.index') }}" class="nav-item {{ Request::is('master/sparepart*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i>
        <span>Sparepart</span>
    </a>

    <a href="{{ route('transaksi.baru') }}" class="nav-item {{ request()->is('transaksi*') ? 'active' : '' }}">
        <i class="bi bi-plus-lg"></i>
         <span>Transaksi Baru</span>

    </a>

    <a href="{{ route('laporan.sparepart') }}" class="nav-item {{ request()->is('laporan/sparepart*') ? 'active' : '' }}">
        <i class="bi bi-boxes"></i>
        <span>Riwayat Servis</span>
    </a>

    <a href="{{ route('laporan.keuangan') }}" class="nav-item {{ request()->is('laporan/keuangan*') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i>
        <span> Keuangan</span>
    </a>

</div>
<div id="rotate-lock">
    <div class="rotate-box">
        <i class="bi bi-phone"></i>
        <p>Silakan putar perangkat ke posisi <b>Portrait</b></p>
    </div>
</div>
</body>
</html>
