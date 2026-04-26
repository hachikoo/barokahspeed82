<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konsumen;
use App\Models\Kendaraan;
use App\Models\Sparepart;
use App\Models\Jasa;
use App\Models\Stok;
use App\Models\MutasiStok;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BengkelController extends Controller
{
    // --- MODUL KONSUMEN ---
    public function index()
    {
        $today = date('Y-m-d');

        // 1. Omset
        try {
            $omset_hari_ini = \App\Models\Transaksi::whereDate('created_at', $today)->sum('total_harga');
        } catch (\Exception $e) {
            $omset_hari_ini = 0;
        }

        // 2. Unit Servis & Beli Part
        $total_servis = \App\Models\Transaksi::whereDate('created_at', $today)->whereNotNull('kendaraan_id')->count();
        $total_beli_part = \App\Models\Transaksi::whereDate('created_at', $today)->whereNull('kendaraan_id')->count();

        // 3. Stok Terendah (Query ini yang bikin error tadi)
        try {
            // Saya ganti ke 'stok' atau 'jumlah', silakan ganti jika Mas tahu nama aslinya
            $stok_terendah = \App\Models\Sparepart::orderBy('stok', 'asc')->take(5)->get();
        } catch (\Exception $e) {
            // Jika masih error, kita ambil 5 data teratas saja tanpa urut stok biar GAK ERROR
            $stok_terendah = \App\Models\Sparepart::take(5)->get();
        }

        $stok_terlaris = [];

        return view('dashboard', compact('omset_hari_ini', 'total_servis', 'total_beli_part', 'stok_terendah', 'stok_terlaris'));
    }

    public function store(Request $request)
    {
        $konsumen = Konsumen::updateOrCreate(
            ['id_konsumen' => $request->id_konsumen],
            [
                'nama'   => $request->nama,
                'telp'   => $request->telp,
                'alamat' => $request->alamat,
            ]
        );

        if ($request->id_konsumen) {
            Kendaraan::where('id_konsumen', $request->id_konsumen)->delete();
        }

        if ($request->nopol) {
            foreach ($request->nopol as $key => $nopol) {
                if ($nopol != null) {
                    Kendaraan::create([
                        'id_konsumen' => $konsumen->id_konsumen,
                        'nopol' => strtoupper($nopol),
                        'jenis_kendaraan' => strtoupper($request->jenis[$key])
                    ]);
                }
            }
        }
        return redirect('/konsumen')->with('sukses', 'Data Konsumen Berhasil Disimpan');
    }

    public function destroy($id)
    {
        Konsumen::findOrFail($id)->delete();
        return redirect('/konsumen');
    }

    // --- MODUL SPAREPART ---


    // Modul Master Part & Stok
    public function sparepart(Request $request)
    {
        // Gunakan leftJoin agar sparepart tanpa stok tetap muncul
        $query = Sparepart::leftJoin('stoks', 'spareparts.id', '=', 'stoks.sparepart_id')
            ->select('spareparts.*', 'stoks.jumlah_stok as stok_sekarang'); // Beri alias yang jelas

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('spareparts.nama_part', 'like', "%$search%")
                    ->orWhere('spareparts.kode_part', 'like', "%$search%");
            });
        }

        // Filter Kategori & Rak
        if ($request->filled('kategori')) $query->where('spareparts.kategori', $request->kategori);
        if ($request->filled('rak')) $query->where('spareparts.rak', $request->rak);

        // Perbaikan Sorting logic
        $sort = $request->get('sort', 'nama_part');
        $order = $request->get('order', 'asc');

        if ($sort == 'stok_sekarang' || $sort == 'stok_jumlah') {
            $query->orderByRaw("COALESCE(stoks.jumlah_stok, 0) $order");
        } else {
            // Ini akan menangani nama_part maupun kode_part secara otomatis
            $query->orderBy("spareparts.$sort", $order);
        }

        $sparepart = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'data' => $sparepart->items(),
                'next_page' => $sparepart->nextPageUrl(),
                'total' => $sparepart->total()
            ]);
        }

        $listKategori = Sparepart::whereNotNull('kategori')->distinct()->pluck('kategori');
        $listRak = Sparepart::whereNotNull('rak')->distinct()->pluck('rak');

        return view('sparepart', compact('sparepart', 'listKategori', 'listRak'));
    }

    public function storeSparepart(Request $request)
    {
        $id = $request->id_part;
        $kodePart = $request->kode_part;

        // 1. Auto Generate jika baru dan kosong
        if (!$id && empty($kodePart)) {
            $lastPart = Sparepart::orderBy('id', 'desc')->first();
            $nextNumber = $lastPart ? ($lastPart->id + 1) : 1;
            $kodePart = 'PRT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        // Masukkan kode_part ke request agar bisa divalidasi
        $request->merge(['kode_part' => $kodePart]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'kategori' => 'required',
            'rak' => 'required',
            'kode_part' => 'required|unique:spareparts,kode_part,' . $id,
        ], [
            'nama.required' => 'Nama sparepart wajib diisi!',
            'kode_part.unique' => 'Kode Part sudah terdaftar!',
            'kode_part.required' => 'Kode Part tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hargaBeli = (int) str_replace('.', '', $request->harga_beli);
        $hargaJual = (int) str_replace('.', '', $request->harga_jual);

        if ($hargaJual < $hargaBeli) {
            return response()->json([
                'errors' => ['harga_jual' => ['Harga jual tidak boleh di bawah harga beli!']]
            ], 422);
        }

        $data = [
            'kode_part' => strtoupper($kodePart),
            'nama_part' => strtoupper($request->nama),
            'kategori'  => strtoupper($request->kategori),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'rak'       => strtoupper($request->rak),
        ];

        $sparepart = Sparepart::updateOrCreate(['id' => $id], $data);

        // Stok awal (Sekarang menggunakan 'Stok::' agar import aktif)
        if (!$id) {
            Stok::updateOrCreate(
                ['sparepart_id' => $sparepart->id],
                ['jumlah_stok' => $request->stok_awal ?? 0]
            );
        }

        return response()->json(['success' => 'Data berhasil disimpan']);
    }

    public function indexStok(Request $request)
    {
        $query = Sparepart::with('stok');
        if ($request->filled('search')) {
            $query->where('nama_part', 'like', '%' . $request->search . '%')
                ->orWhere('kode_part', 'like', '%' . $request->search . '%');
        }
        $stok = $query->orderBy('nama_part', 'asc')->paginate(10);
        return view('stok', compact('stok'));
    }
    public function updateStok(Request $request)
    {
        // Validasi input
        $request->validate([
            'sparepart_id' => 'required',
            'tipe' => 'required|in:masuk,keluar',
            'qty' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            // 1. Update atau Buat data di tabel 'stoks'
            $stok = Stok::where('sparepart_id', $request->sparepart_id)->first();

            if (!$stok) {
                $stok = new Stok();
                $stok->sparepart_id = $request->sparepart_id;
                $stok->jumlah_stok = 0;
            }

            if ($request->tipe == 'masuk') {
                $stok->jumlah_stok += $request->qty;
            } else {
                if ($stok->jumlah_stok < $request->qty) {
                    return response()->json(['error' => 'Stok tidak mencukupi!'], 400);
                }
                $stok->jumlah_stok -= $request->qty;
            }
            $stok->save();

            // 2. Catat riwayat di tabel 'mutasi_stoks'
            MutasiStok::create([
                'sparepart_id' => $request->sparepart_id,
                'tipe' => $request->tipe,
                'qty' => $request->qty,
                'created_at' => now()
            ]);

            DB::commit();
            return response()->json(['success' => 'Stok berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroySparepart($id)
    {
        Sparepart::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data Sparepart Berhasil Dihapus');
    }

    // --- MODUL BARCODE ---




    // --- MODUL JASA ---
    public function indexJasa(Request $request)
    {
        $query = \App\Models\Jasa::query();

        if ($request->filled('search')) {
            $query->where('nama_jasa', 'like', '%' . $request->search . '%');
        }

        // Menggunakan paginate(10) untuk membatasi 10 data per halaman
        $jasa = $query->orderBy('nama_jasa', 'asc')->paginate(10);

        return view('jasa', compact('jasa'));
    }

    public function storeJasa(Request $request)
    {
        $harga = str_replace('.', '', $request->harga);

        Jasa::updateOrCreate(
            ['id_jasa' => $request->id_jasa],
            [
                'nama_jasa' => strtoupper($request->nama_jasa),
                'harga'     => $harga,
            ]
        );

        return redirect()->back()->with('success', 'Data Jasa berhasil diperbarui!');
    }

    public function destroyJasa($id)
    {
        Jasa::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jasa berhasil dihapus!');
    }
}
