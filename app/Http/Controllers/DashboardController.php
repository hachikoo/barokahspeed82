<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Sparepart;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // --- 1. DATA STATS (Angka Card) ---
        // Kita gunakan closure reusable untuk menghitung servis agar kode lebih bersih
        $queryServis = function ($query) {
            return $query->whereHas('details', function ($q) {
                $q->where('tipe', 'jasa');
            });
        };

        $stats = [
            'harian' => [
                'label' => 'Hari Ini',
                'omset' => (int) Transaksi::whereDate('created_at', $hariIni)->sum('total_harga'),
                'servis' => (int) Transaksi::whereDate('created_at', $hariIni)->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count(),
                'part' => (int) DB::table('transaksi_details')->whereDate('created_at', $hariIni)->where('tipe', 'sparepart')->sum('qty'),
            ],
            'mingguan' => [
                'label' => 'Minggu Ini',
                'omset' => (int) Transaksi::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_harga'),
                'servis' => (int) Transaksi::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count(),
                'part' => (int) DB::table('transaksi_details')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('tipe', 'sparepart')->sum('qty'),
            ],
            'bulanan' => [
                'label' => 'Bulan Ini',
                'omset' => (int) Transaksi::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->sum('total_harga'),
                'servis' => (int) Transaksi::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count(),
                'part' => (int) DB::table('transaksi_details')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->where('tipe', 'sparepart')->sum('qty'),
            ],
            'tahunan' => [
                'label' => 'Tahun Ini',
                'omset' => (int) Transaksi::whereYear('created_at', $tahunIni)->sum('total_harga'),
                'servis' => (int) Transaksi::whereYear('created_at', $tahunIni)->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count(),
                'part' => (int) DB::table('transaksi_details')->whereYear('created_at', $tahunIni)->where('tipe', 'sparepart')->sum('qty'),
            ],
        ];

        // --- 2. DATA GRAFIK DUA SISI ---
        $chartData = [];

        // --- A. Logika Mingguan (7 Hari Terakhir) ---
        $startOfMonth = now()->startOfMonth();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData['mingguan']['labels'][] = $date->format('d M');

            $chartData['mingguan']['omset'][] = (int) Transaksi::whereDate('created_at', $date->toDateString())->sum('total_harga');

            $chartData['mingguan']['servis'][] = (int) Transaksi::whereDate('created_at', $date->toDateString())
                ->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count();

            $chartData['mingguan']['part'][] = (int) DB::table('transaksi_details')
                ->whereDate('created_at', $date->toDateString())
                ->where('tipe', 'sparepart')
                ->sum('qty');
        }

        // --- B. Logika Harian (Breakdown Jam 08:00 - 20:00) ---
        for ($h = 8; $h <= 20; $h++) {
            $jamFormat = str_pad($h, 2, '0', STR_PAD_LEFT);
            $chartData['harian']['labels'][] = $jamFormat . ':00';
            $chartData['harian']['omset'][] = (int) Transaksi::whereDate('created_at', $hariIni)->whereRaw('HOUR(created_at) = ?', [$h])->sum('total_harga');
            $chartData['harian']['servis'][] = (int) Transaksi::whereDate('created_at', $hariIni)->whereRaw('HOUR(created_at) = ?', [$h])->whereHas('details', function ($q) {
                $q->where('tipe', 'jasa');
            })->count();
            $chartData['harian']['part'][] = (int) Transaksi::whereDate('created_at', $hariIni)->whereRaw('HOUR(created_at) = ?', [$h])->whereDoesntHave('details', function ($q) {
                $q->where('tipe', 'jasa');
            })->count();
        }

        // --- C. Logika Bulanan (Breakdown per Minggu) ---
        $startOfMonth = now()->startOfMonth();
        for ($w = 1; $w <= 4; $w++) {
            $chartData['bulanan']['labels'][] = "Minggu $w";

            $from = $startOfMonth->copy()->addDays(($w - 1) * 7)->startOfDay();
            // Jika minggu ke-4, ambil sampai akhir bulan agar sisa hari terhitung
            $to = ($w == 4) ? now()->endOfMonth() : $startOfMonth->copy()->addDays(($w * 7) - 1)->endOfDay();

            $chartData['bulanan']['omset'][] = (int) Transaksi::whereBetween('created_at', [$from, $to])->sum('total_harga');

            $chartData['bulanan']['servis'][] = (int) Transaksi::whereBetween('created_at', [$from, $to])
                ->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count();

            // Query Part menggunakan DB::table untuk akurasi Qty Pcs
            $chartData['bulanan']['part'][] = (int) DB::table('transaksi_details')
                ->whereBetween('created_at', [$from, $to])
                ->where('tipe', 'sparepart')
                ->sum('qty');
        }

        // --- D. Logika Tahunan (Breakdown per Bulan) ---
        // --- D. Logika Tahunan (Breakdown per Bulan REAL) ---
        for ($m = 1; $m <= 12; $m++) {
            $chartData['tahunan']['labels'][] = Carbon::create()->month($m)->format('M');

            // Pastikan filter tahun ini juga ikut supaya data tahun lalu tidak nyampur
            $chartData['tahunan']['omset'][] = (int) Transaksi::whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $m)->sum('total_harga');

            $chartData['tahunan']['servis'][] = (int) Transaksi::whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $m)
                ->whereHas('details', function ($q) {
                    $q->where('tipe', 'jasa');
                })->count();

            $chartData['tahunan']['part'][] = (int) DB::table('transaksi_details')
                ->whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $m)
                ->where('tipe', 'sparepart')
                ->sum('qty');
        }
        // --- 3. DETEKSI KOLOM STOK ---
        $semuaKolomPart = DB::getSchemaBuilder()->getColumnListing('spareparts');
       $kolomStok = in_array('stok_akhir', $semuaKolomPart) ? 'stok_akhir' : (in_array('stok', $semuaKolomPart) ? 'stok' : 'jumlah');

        // --- 4. DATA LAINNYA ---
        $palingLaku = DB::table('transaksi_details')
            ->select('nama_item', DB::raw('SUM(qty) as total_terjual'))
            ->where('tipe', 'sparepart')
            ->groupBy('nama_item')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)->get();
$stokRendah = DB::table('spareparts')
            ->whereRaw("$kolomStok <= IFNULL(stok_min, 2)") 
            ->orderBy($kolomStok, 'asc')
            ->get();       
            
           // --- 5. DATA LAINNYA ---
        $palingLaku = DB::table('transaksi_details')
            ->select('nama_item', DB::raw('SUM(qty) as total_terjual'))
            ->where('tipe', 'sparepart')
            ->groupBy('nama_item')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)->get();

        return view('dashboard', compact('stats', 'chartData', 'stokRendah', 'kolomStok', 'palingLaku'));
    }
}
