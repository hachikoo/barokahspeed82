<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaksi, Sparepart, Mekanik, Jasa, Unit, Kendaraan, Konsumen};
use Illuminate\Support\Facades\{DB, Log};

class TransaksiController extends Controller
{
    /**
     * Tampilan Form Transaksi Servis
     */
    public function createServis()
    {
        $data = [
            'mekaniks'      => Mekanik::aktif()->get(),
            'jasas'         => Jasa::all(),
            'list_part'     => Sparepart::where('status', 1)->get(),
            'merks'         => Unit::select('merk')->distinct()->orderBy('merk', 'asc')->get(),
            'units'         => Unit::all(), // Tetap ada untuk keperluan @json di Blade
            'kategori_jasa' => Jasa::select('kategori')->distinct()->pluck('kategori'),
        ];

        return view('transaksi.servis', $data);
    }
    /**
     * Simpan Transaksi Baru
     */
    public function store(Request $request)
    {
        $kolomStok = $this->getNamaKolomStok();

        DB::beginTransaction();
        try {
            // 1. Normalisasi Data
            $noPolisi = $request->filled('no_polisi') ? strtoupper(str_replace(' ', '', $request->no_polisi)) : null;
            $konsumen_id = null;
            $kendaraan_id = null;

            // 2. Logic Konsumen (Berdasarkan No WA)
            if ($request->filled('no_wa')) {
                $konsumen = Konsumen::updateOrCreate(
                    ['no_wa' => $request->no_wa],
                    [
                        'nama_konsumen' => strtoupper($request->nama_pelanggan ?? 'PELANGGAN'),
                        'alamat'        => strtoupper($request->alamat ?? '-')
                    ]
                );
                $konsumen_id = $konsumen->id;
            }

            // 3. Logic Kendaraan (Berdasarkan No Polisi)
            if ($noPolisi) {
                $kendaraan = Kendaraan::updateOrCreate(
                    ['no_polisi' => $noPolisi],
                    [
                        'konsumen_id' => $konsumen_id,
                        'unit_id'     => $request->select_tipe ?? $request->unit_id, // Ambil dari dropdown tipe
                        'merk_tipe'   => strtoupper($request->merk_tipe ?? '-')
                    ]
                );
                $kendaraan_id = $kendaraan->id;
            }

            // 4. Simpan Header Transaksi
            $transaksi = Transaksi::create([
                'no_faktur'    => 'INV-' . date('YmdHis'),
                'no_polisi'    => $noPolisi,
                'konsumen_id'  => $konsumen_id,
                'kendaraan_id' => $kendaraan_id,
                'mekanik_id'   => $request->mekanik_id,
                'total_harga'  => 0, // Akan diupdate setelah loop
                'bayar'        => $request->bayar ?? 0,
                'kembali'      => 0,
            ]);

            $grandTotal = 0;

            // 5. Simpan Detail Jasa
            if ($request->has('jasa_id')) {
                foreach (array_filter($request->jasa_id) as $idJasa) {
                    $jasa = Jasa::find($idJasa);
                    if ($jasa) {
                        $transaksi->details()->create([
                            'nama_item'    => strtoupper($jasa->nama_jasa),
                            'tipe'         => 'jasa',
                            'qty'          => 1,
                            'harga_satuan' => $jasa->biaya_jasa,
                            'subtotal'     => $jasa->biaya_jasa,
                        ]);
                        $grandTotal += $jasa->biaya_jasa;
                    }
                }
            }

            // 6. Simpan Detail Sparepart & Potong Stok
            if ($request->has('sparepart_id')) {
                foreach ($request->sparepart_id as $key => $idPart) {
                    if (!$idPart) continue;

                    $qty = $request->qty[$key] ?? 1;
                    $part = Sparepart::find($idPart);

                    if ($part) {
                        $sub = $part->harga_jual * $qty;
                        $transaksi->details()->create([
                            'sparepart_id' => $idPart,
                            'nama_item'    => strtoupper($part->nama_part),
                            'tipe'         => 'sparepart',
                            'qty'          => $qty,
                            'harga_satuan' => $part->harga_jual,
                            'subtotal'     => $sub,
                        ]);

                        // Update Stok secara otomatis
                        $part->decrement($kolomStok, $qty);
                        $grandTotal += $sub;
                    }
                }
            }

            // 7. Update Final Total & Kembalian
            $transaksi->update([
                'total_harga' => $grandTotal,
                'kembali'     => ($request->bayar ?? 0) - $grandTotal
            ]);

            DB::commit();

            return redirect()->route('transaksi.cetak', $transaksi->id)
                ->with('success', 'Transaksi Berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Transaksi Gagal: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * AJAX: Cari Nama berdasarkan No WA
     */
    // AJAX: Cek Konsumen via WA
    public function cekKonsumen(Request $request)
    {
        $konsumen = \App\Models\Konsumen::where('no_wa', $request->no_wa)->first();

        return response()->json([
            'status' => $konsumen ? 'found' : 'not_found',
            'nama'   => $konsumen ? $konsumen->nama_konsumen : null
        ]);
    }

    // Route: transaksi.cek-kendaraan-lengkap
    public function getKendaraanLengkap($no_polisi)
    {
        $nopol = strtoupper(str_replace(' ', '', $no_polisi));
        $kendaraan = \App\Models\Kendaraan::where('no_polisi', $nopol)->with('unit')->first();

        if ($kendaraan) {
            return response()->json([
                'status'  => 'found',
                'merk'    => $kendaraan->unit->merk,
                'unit_id' => $kendaraan->unit_id
            ]);
        }
        return response()->json(['status' => 'not_found']);
    }

    public function simpanCepat(Request $request)
    {
        $unit = Unit::create([
            'merk' => strtoupper($request->merk),
            'nama_unit' => strtoupper($request->nama_unit),
            'status' => 1 // aktif
        ]);
        return response()->json($unit);
    }

    public function storeUnitAjax(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_unit' => 'required|string|max:255',
            'merk' => 'required|string'
        ]);

        // Simpan ke table units
        $unit = Unit::create([
            'nama_unit' => strtoupper($request->nama_unit), // Biar rapi pakai uppercase
            'merk'      => strtoupper($request->merk),
        ]);

        // Kembalikan respon JSON
        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil disimpan',
            'data'    => $unit
        ]);
    }

    /**
     * Cetak Struk
     */
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['details', 'mekanik', 'konsumen', 'kendaraan.unit'])->findOrFail($id);
        return view('transaksi.cetak_struk', compact('transaksi'));
    }

    /**
     * Helper: Mendeteksi nama kolom stok
     */
    private function getNamaKolomStok()
    {
        $semuaKolom = DB::getSchemaBuilder()->getColumnListing('spareparts');
        $kemungkinan = ['stok', 'jumlah_stok', 'stok_akhir', 'qty'];

        foreach ($kemungkinan as $nama) {
            if (in_array($nama, $semuaKolom)) return $nama;
        }
        return 'stok';
    }
}
