<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Sparepart;
use App\Models\Service; // Tambahkan ini agar riwayatServis tidak error
use Illuminate\Support\Facades\DB; // WAJIB ADA agar tidak error "Class DB not found"
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception; // WAJIB ADA agar block catch (Exception $e) bisa jalan

class LaporanController extends Controller
{
    // 1. Fungsi Filter Terpusat
    private function applyFilter($query, $request)
    {
        $filter = $request->get('filter', 'minggu_ini');
        
        if ($filter == 'hari_ini') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter == 'minggu_ini') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter == 'bulan_ini') {
            $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        } elseif ($filter == 'custom' && $request->filled('tgl_mulai')) {
            $tgl_mulai = $request->tgl_mulai . " 00:00:00";
            $tgl_selesai = ($request->tgl_selesai ?? $request->tgl_mulai) . " 23:59:59";
            $query->whereBetween('created_at', [$tgl_mulai, $tgl_selesai]);
        }

        if ($request->filled('search')) {
            $query->where('no_faktur', 'LIKE', '%' . $request->search . '%');
        }

        return $query;
    }

    // 2. Laporan Servis (Unit Masuk)
    public function index(Request $request)
    {
        $query = Transaksi::with(['konsumen', 'kendaraan', 'mekanik', 'details']);
        $query->whereHas('details', function ($q) { $q->where('tipe', 'jasa'); });
        $query = $this->applyFilter($query, $request);
        
        $laporan = $query->latest()->get();

        $summary = [
            'total_unit' => $laporan->count(),
            'total_omzet' => $laporan->sum('total_harga'),
            'total_jasa' => $laporan->flatMap->details->where('tipe', 'jasa')->sum('subtotal'),
            'total_part' => $laporan->flatMap->details->where('tipe', 'sparepart')->sum('subtotal'),
        ];

        return view('laporan.laporanservis', compact('laporan', 'summary'));
    }

    // 3. Laporan Keuangan (Profit & Loss)
    public function laporanKeuangan(Request $request)
    {
        $query = Transaksi::with(['details.sparepart']);
        $query = $this->applyFilter($query, $request);

        $allData = $query->get();
        $totalPart = 0; $totalServis = 0; $totalMargin = 0;

        foreach ($allData as $t) {
            $partItems = $t->details->where('tipe', 'sparepart');
            $servisItems = $t->details->where('tipe', 'jasa');
            $totalPart += $partItems->sum('subtotal');
            $totalServis += $servisItems->sum('subtotal');
            $totalMargin += $partItems->sum(fn($d) => ($d->harga_satuan - ($d->sparepart->harga_beli ?? 0)) * $d->qty);
        }

        $summaryData = [
            'part' => $totalPart,
            'servis' => $totalServis,
            'kotor' => $totalPart + $totalServis,
            'margin' => $totalMargin,
            'bersih' => $totalMargin + $totalServis
        ];

        $sort = $request->get('sort', 'desc');
        $transaksiPaginated = $query->orderBy('total_harga', $sort)->paginate(15);

        $transaksiPaginated->getCollection()->transform(function ($t) {
            $part = $t->details->where('tipe', 'sparepart');
            $servis = $t->details->where('tipe', 'jasa');
            $margin = $part->sum(fn($d) => ($d->harga_satuan - ($d->sparepart->harga_beli ?? 0)) * $d->qty);

            return [
                'no_faktur' => $t->no_faktur,
                'tanggal'   => $t->created_at->format('d/m/Y'),
                'jual_part' => $part->sum('subtotal'),
                'margin_part' => $margin,
                'servis'    => $servis->sum('subtotal'),
                'omzet_kotor' => $t->total_harga,
                'omzet_bersih' => $margin + $servis->sum('subtotal')
            ];
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('laporan.partials.item', ['laporan' => $transaksiPaginated])->render(),
                'hasMore' => $transaksiPaginated->hasMorePages(),
                'summary' => [
                    'part' => number_format($summaryData['part'], 0, ',', '.'),
                    'servis' => number_format($summaryData['servis'], 0, ',', '.'),
                    'kotor' => number_format($summaryData['kotor'], 0, ',', '.'),
                    'margin' => number_format($summaryData['margin'], 0, ',', '.'),
                    'bersih' => number_format($summaryData['bersih'], 0, ',', '.')
                ]
            ]);
        }

        return view('laporan.keuangan', [
            'laporan' => $transaksiPaginated,
            'summary' => $summaryData
        ]);
    }

    // 4. Modal Detail
    public function getDetailModal($no_faktur)
    {
        $transaksi = Transaksi::with('details')->where('no_faktur', $no_faktur)->first();
        return $transaksi ? view('laporan.modal_detail_isi', compact('transaksi')) : "Data tidak ditemukan";
    }

    // 5. Export PDF
    public function exportPdf(Request $request)
    {
        $query = Transaksi::with(['details.sparepart']);
        $query = $this->applyFilter($query, $request);
        $laporan = $query->orderBy('created_at', 'desc')->get();

        return Pdf::loadView('laporan.keuangan_pdf', compact('laporan'))
                  ->setPaper('a4', 'landscape')
                  ->download('Laporan_Keuangan_'.now()->format('dmy').'.pdf');
    }

    // 6. Penjualan Sparepart
  public function penjualanSparepart(Request $request)
{
    try {
        // 1. Logika Filter Tanggal
        $filter = $request->get('filter', 'bulan_ini');
        $mulai = date('Y-m-01');
        $selesai = date('Y-m-d');

        if ($filter == 'hari_ini') {
            $mulai = date('Y-m-d');
        } elseif ($filter == 'minggu_ini') {
            $mulai = date('Y-m-d', strtotime('-7 days'));
        } elseif ($filter == 'custom') {
            $mulai = $request->get('tgl_mulai');
            $selesai = $request->get('tgl_selesai');
        }

        // 2. Ambil Data Transaksi (untuk bagian Rincian Per Faktur)
        // Kita pakai Eager Loading (with) supaya tidak berat saat looping di Blade
        $transaksi = \App\Models\Transaksi::with(['details.sparepart'])
            ->whereHas('details', function($q) {
                $q->where('tipe', 'sparepart');
            })
            ->whereBetween('created_at', [$mulai . " 00:00:00", $selesai . " 23:59:59"])
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Olah Data untuk Rekapitulasi Item & Summary
        // Kita kumpulkan semua detail sparepart dari semua transaksi di atas
        $allDetails = collect();
        foreach ($transaksi as $t) {
            foreach ($t->details->where('tipe', 'sparepart') as $d) {
                $allDetails->push($d);
            }
        }

        // 4. Hitung Rekap Per Jenis Barang (Variabel $rekapSparepart)
        $rekapSparepart = $allDetails->groupBy('sparepart_id')->map(function ($items) {
            $first = $items->first();
            return [
                'nama_item'    => $first->nama_item,
                'total_qty'    => $items->sum('qty'),
                'total_omzet'  => $items->sum('subtotal'),
                'total_margin' => $items->sum(function($item) {
                    $hargaBeli = $item->sparepart->harga_beli ?? 0;
                    return ($item->harga_satuan - $hargaBeli) * $item->qty;
                })
            ];
        });

        // 5. Hitung Total Keseluruhan (Variabel $summary)
        $summary = [
            'total_pcs'    => $allDetails->sum('qty'),
            'total_omzet'  => $allDetails->sum('subtotal'),
            'total_margin' => $allDetails->sum(function($item) {
                $hargaBeli = $item->sparepart->harga_beli ?? 0;
                return ($item->harga_satuan - $hargaBeli) * $item->qty;
            })
        ];

        return view('laporan.laporansparepart', compact('transaksi', 'rekapSparepart', 'summary', 'mulai', 'selesai'));

    } catch (\Exception $e) {
        return "Gagal memuat laporan: " . $e->getMessage();
    }
}
    // 7. Riwayat Servis
   public function riwayatServis(Request $request)
    {
        try {
            $mulai = $request->get('tgl_mulai') ?? date('Y-m-01');
            $selesai = $request->get('tgl_selesai') ?? date('Y-m-d');

            // Kita gunakan model Transaksi yang sudah Bos miliki
            $laporan = Transaksi::with(['konsumen', 'details.sparepart'])
                ->whereHas('details', function($q) {
                    $q->where('tipe', 'jasa');
                })
                ->whereBetween('created_at', [$mulai . " 00:00:00", $selesai . " 23:59:59"])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('laporan.servis', compact('laporan', 'mulai', 'selesai'));
            
        } catch (Exception $e) {
            return "Gagal memuat riwayat: " . $e->getMessage();
        }
    }
}