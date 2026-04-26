@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container-fluid px-4 py-4">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <h4 class="fw-bold text-dark mb-0">Dashboard</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item small text-muted">Barokah Speed</li>
                        <li class="breadcrumb-item small active" aria-current="page">Warehouse System</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center mt-2 justify-content-center justify-content-md-start">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm me-3">
                        <i class="bi bi-calendar3 me-2"></i>{{ date('l, d F Y') }}
                    </span>
                    <span class="badge bg-dark px-3 py-2 rounded-pill shadow-sm" id="digitalClock"
                        style="font-family: 'JetBrains Mono', monospace; letter-spacing: 1px;">00:00:00</span>
                </div>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="btn-group shadow-sm rounded-pill p-1 bg-white" role="group">
                    <button type="button" class="btn btn-primary rounded-pill px-4 filter-btn" data-filter="harian">Hari
                        Ini</button>
                    <button type="button" class="btn btn-light rounded-pill px-4 filter-btn" data-filter="mingguan">Minggu
                        Ini</button>
                    <button type="button" class="btn btn-light rounded-pill px-4 filter-btn" data-filter="bulanan">Bulan
                        Ini</button>
                    <button type="button" class="btn btn-light rounded-pill px-4 filter-btn" data-filter="tahunan">Tahun
                        ini</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-primary border-4">
                <div class="card-body p-4">
                    <small class="text-muted fw-bold text-uppercase ls-1" id="label-omset">Omset Hari Ini</small>
                    <h2 class="fw-bold text-primary mb-0 mt-1" id="val-omset">Rp
                        {{ number_format($stats['harian']['omset'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-dark border-4">
                <div class="card-body p-4 position-relative" style="overflow: hidden;">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10" style="z-index: 0;">
                        <i class="bi bi-wrench-adjustable display-4"></i>
                    </div>

                    <div class="position-relative" style="z-index: 1;">
                        <small class="text-muted fw-bold text-uppercase ls-1" id="label-servis">Pelayanan Servis</small>
                        <h2 class="fw-bold text-dark mb-0 mt-1">
                            <span id="val-servis">{{ $stats['harian']['servis'] }}</span>
                            <small class="fs-6 fw-normal">Motor</small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-info border-4">
                <div class="card-body p-4 position-relative" style="overflow: hidden;">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10" style="z-index: 0;">
                        <i class="bi bi-cart4 display-4"></i>
                    </div>

                    <div class="position-relative" style="z-index: 1;">
                        <small class="text-muted fw-bold text-uppercase ls-1" id="label-part">Penjualan Part</small>
                        <h2 class="fw-bold text-info mb-0 mt-1">
                            <span id="val-part">{{ $stats['harian']['part'] }}</span>
                            <small class="fs-6 fw-normal">Pcs</small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 py-4 px-4 d-flex align-items-center">
                        <div class="bg-primary-subtle p-2 rounded-3 me-3">
                            <i class="bi bi-graph-up-arrow text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Tren Omset Mingguan</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="position: relative; height: 300px;"> <canvas id="omsetChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 py-4 px-4 d-flex align-items-center">
                        <div class="bg-dark-subtle p-2 rounded-3 me-3">
                            <i class="bi bi-pie-chart-fill text-dark"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Tipe Kedatangan</h6>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 300px;">
                            <canvas id="perbandinganChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div
                        class="card-header bg-transparent border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Stok Menipis</h6>

<span class="badge bg-danger-subtle text-danger rounded-pill px-3">Dibawah Limit Stok</span>
                    </div>
                    <div class="table-responsive px-4 pb-4" style="max-height: 350px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="border-0 py-3">Nama Sparepart</th>
                                    <th class="border-0 py-3 text-center">Sisa</th>
                                    <th class="border-0 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokRendah as $s)
    <tr>
        <td class="border-0 py-3 fw-semibold">
            {{ $s->nama_part ?? $s->nama_sparepart }}
            <div class="small text-muted fw-normal">Limit: {{ $s->stok_min ?? 2 }}</div> </td>
        <td class="border-0 py-3 text-center">
            <span class="badge {{ $s->$kolomStok <= 0 ? 'bg-dark' : 'bg-danger' }} rounded-pill">
                {{ $s->$kolomStok }}
            </span>
        </td>
        <td class="border-0 py-3 text-end">
            <button class="btn btn-sm btn-outline-dark rounded-circle"
                onclick="bukaModalStok('{{ $s->id }}', '{{ $s->nama_part ?? $s->nama_sparepart }}')">
                <i class="bi bi-plus"></i>
            </button>
        </td>
    </tr>
@empty
    @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 py-4 px-4">
                        <h6 class="fw-bold text-success mb-0"><i class="bi bi-fire me-2"></i>Part Paling Laku</h6>
                    </div>
                    <div class="table-responsive px-4 pb-4">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="border-0 py-3">Item</th>
                                    <th class="border-0 py-3 text-center">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($palingLaku as $laku)
                                    <tr>
                                        <td class="border-0 py-3 fw-semibold">{{ $laku->nama_item }}</td>
                                        <td class="border-0 py-3 text-center">
                                            <span
                                                class="badge bg-success-subtle text-success px-3 py-2 rounded-3 fw-bold">{{ $laku->total_terjual }}
                                                pcs</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted">Belum ada data barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMutasiStok" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form id="formTambahStokDashboard">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold">Tambah Stok</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <input type="hidden" name="id" id="input_part_id">
                        <p class="text-muted small mb-1">Part Name:</p>
                        <h5 id="tampil_nama_part" class="fw-bold text-dark mb-4"></h5>

                        <div class="mb-3">
                            <input type="number" name="jumlah" id="input_jumlah_dash"
                                class="form-control form-control-lg text-center rounded-3 shadow-sm" placeholder="0"
                                required min="1" autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow">Update Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .ls-1 {
            letter-spacing: 1px;
        }

        .bg-primary-subtle {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-dark-subtle {
            background-color: rgba(33, 37, 41, 0.1);
        }

        #val-omset {
            word-break: break-all;
            font-size: calc(1.2rem + 1vw);
            /* Ukuran font dinamis */
        }

        /* ==================================================
   RESPONSIVE MOBILE DASHBOARD
   Desktop tetap aman, hanya aktif di <=768px
================================================== */
@media (max-width:768px){

    .container-fluid{
        padding-left:12px !important;
        padding-right:12px !important;
        padding-top:12px !important;
        padding-bottom:12px !important;
    }

    /* Header */
    h4{
        font-size:22px !important;
        text-align:center;
    }

    .breadcrumb{
        justify-content:center;
        margin-top:4px;
    }

    #digitalClock{
        font-size:13px !important;
        min-width:95px;
    }

    /* Tombol filter */
    .btn-group{
        width:100%;
        display:grid !important;
        grid-template-columns:repeat(2,1fr);
        gap:8px;
        padding:0 !important;
        background:transparent !important;
        box-shadow:none !important;
    }

    .btn-group .btn{
        border-radius:12px !important;
        min-height:44px;
        font-size:13px;
        padding:8px;
    }

    /* Card umum */
    .card{
        border-radius:16px !important;
    }

    .card-body,
    .card-header{
        padding:14px !important;
    }

    /* Stat card */
    #val-omset,
    #val-servis,
    #val-part{
        font-size:1.45rem !important;
        line-height:1.2;
        word-break:break-word;
    }

    small{
        font-size:11px !important;
    }

    /* Chart */
    #omsetChart,
    #perbandinganChart{
        max-height:240px !important;
    }

    .card-body div[style*="height: 300px"]{
        height:240px !important;
    }

    /* Table area */
    .table-responsive{
        overflow-x:auto !important;
        -webkit-overflow-scrolling:touch;
    }

    table{
        min-width:520px;
    }

    th, td{
        white-space:nowrap;
        font-size:13px !important;
        vertical-align:middle;
    }

    /* Badge */
    .badge{
        font-size:11px !important;
        padding:6px 10px !important;
    }

    /* Modal stok mobile */
    #modalMutasiStok .modal-dialog{
        margin:0 !important;
        max-width:100% !important;
        height:100vh !important;
    }

    #modalMutasiStok .modal-content{
        height:100vh;
        border-radius:0 !important;
        display:flex;
        flex-direction:column;
    }

    #modalMutasiStok .modal-body{
        flex:1;
        overflow-y:auto;
        padding:20px !important;
    }

    #modalMutasiStok .form-control{
        min-height:48px;
        font-size:16px;
    }

    #modalMutasiStok .btn{
        min-height:48px;
        border-radius:12px !important;
    }

    /* spacing antar section */
    .row.g-4,
    .row.g-3{
        --bs-gutter-y: 12px;
    }
}
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Logika Jam Digital
        function updateClock() {
            const now = new Date();
            const clockElement = document.getElementById('digitalClock');
            if (clockElement) {
                clockElement.textContent = now.toLocaleTimeString('en-GB');
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. Logika Modal Stok
        function bukaModalStok(id, nama) {
            document.getElementById('input_part_id').value = id;
            document.getElementById('tampil_nama_part').innerText = nama;
            new bootstrap.Modal(document.getElementById('modalMutasiStok')).show();
        }

        // 3. Data Terpusat dari PHP
        // Pastikan variabel ini hanya dideklarasikan SATU KALI
        const allStats = {!! json_encode($stats) !!};
        const allChartData = {!! json_encode($chartData) !!};
        console.log("Data Grafik:", allChartData);

        // 4. Inisialisasi Chart (Default: Mingguan)
        const ctxOmset = document.getElementById('omsetChart').getContext('2d');
        const ctxComp = document.getElementById('perbandinganChart').getContext('2d');

        const omsetChart = new Chart(ctxOmset, {
            type: 'line',
            data: {
                labels: allChartData['mingguan']['labels'],
                datasets: [{
                    label: 'Omset',
                    data: allChartData['mingguan']['omset'],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const perbandinganChart = new Chart(ctxComp, {
            type: 'bar',
            data: {
                labels: allChartData['mingguan']['labels'],
                datasets: [{
                        label: 'Unit Servis',
                        data: allChartData['mingguan']['servis'],
                        backgroundColor: '#1cc88a'
                    },
                    {
                        label: 'Part Terjual',
                        data: allChartData['mingguan']['part'],
                        backgroundColor: '#36b9cc'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 5. Logika Klik Tombol Filter (Hari/Minggu/Bulan/Tahun)
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // a. Update Visual Tombol
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.replace('btn-primary', 'btn-light');
                });
                this.classList.replace('btn-light', 'btn-primary');

                // b. Update Angka di Card (Stats)
                const s = allStats[filter];
                if (s) {
                    document.getElementById('label-omset').innerText = `Omset ${s.label}`;
                    document.getElementById('val-omset').innerText = 'Rp ' + s.omset.toLocaleString(
                        'id-ID');

                    document.getElementById('label-servis').innerText = `Pelayanan Servis (${s.label})`;
                    document.getElementById('val-servis').innerText = s.servis;

                    document.getElementById('label-part').innerText = `Penjualan Part (${s.label})`;
                    document.getElementById('val-part').innerText = s.part;
                }

                // c. Update Grafik (Chart)
                const c = allChartData[filter];
                if (c) {
                    // Update Line Chart
                    omsetChart.data.labels = c.labels;
                    omsetChart.data.datasets[0].data = c.omset;
                    omsetChart.update();

                    // Update Bar Chart
                    perbandinganChart.data.labels = c.labels;
                    perbandinganChart.data.datasets[0].data = c.servis;
                    perbandinganChart.data.datasets[1].data = c.part;
                    perbandinganChart.update();

                    // Update Judul Grafik agar Dinamis
                    const chartTitle = document.querySelector('.bi-graph-up-arrow').parentElement
                        .nextElementSibling;
                    chartTitle.innerText = `Tren Omset ${s.label}`;
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const formDash = document.getElementById('formTambahStokDashboard');

            // Cek dulu formnya ada atau nggak, biar nggak error 'null'
            if (formDash) {
                formDash.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const data = {
                        id: formData.get('id') || document.getElementById('input_part_id').value,
                        jumlah: formData.get('jumlah')
                    };

                    try {
                        const response = await fetch("{{ route('sparepart.tambah-stok') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        const res = await response.json();

                        if (res.status === 'success') {
                            // Tutup modal
                            const modalEl = document.getElementById('modalMutasiStok');
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();

                            // Tampilkan SweetAlert Detail
                            Swal.fire({
                                icon: 'success',
                                title: 'Stok Diperbarui',
                                html: `Berhasil menambah <b>${res.jumlah_ditambah}</b> item untuk <b>${res.nama}</b>.<br>Total stok sekarang: <b>${res.stok_sekarang}</b>`,
                                confirmButtonColor: '#0d6efd'
                            }).then(() => {
                                // Scroll ke atas dulu dengan halus
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth'
                                });

                                // Beri jeda sebentar agar animasi scroll kelihatan, baru reload
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            });
                        }
                    } catch (error) {
                        console.error("Error update stok:", error);
                        Swal.fire('Gagal', 'Terjadi kesalahan sistem', 'error');
                    }
                });
            } else {
                console.warn("Form 'formTambahStokDashboard' tidak ditemukan di halaman ini.");
            }
        });
    </script>
@endsection
