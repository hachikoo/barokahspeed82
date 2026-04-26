<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SparepartController extends Controller
{
    /**
     * Menampilkan halaman utama sparepart
     */
    public function index()
    {
        // Menggunakan query builder untuk kategori agar lebih ringan
        $categories = Sparepart::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        return view('master.sparepart.index', compact('categories'));
    }

    /**
     * Otomatis generate kode part baru
     */
    public function generateCode()
    {
        $lastPart = Sparepart::orderBy('id', 'desc')->first();
        $nextNumber = $lastPart ? ((int) substr($lastPart->kode_part, 4)) + 1 : 1;
        $newCode = 'PRT-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return response()->json(['kode' => $newCode]);
    }

    /**
     * Mengambil data JSON untuk Datatable/Infinite Scroll
     */
    public function getData(Request $request)
    {
        try {
            $query = Sparepart::query();

            // Jika request datang dari modal transaksi, tambahkan filter ini
            if ($request->has('for_transaction')) {
                $query->where('status', 1);
            }


            // Filter Pencarian
            $query->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                return $q->where(function ($sub) use ($search) {
                    $sub->where('nama_part', 'like', "%{$search}%")
                        ->orWhere('kode_part', 'like', "%{$search}%");
                });
            });

            // Filter Kategori
            $query->when($request->filled('kategori'), function ($q) use ($request) {
                return $q->where('kategori', $request->kategori);
            });

            // Sorting Stok
            if ($request->sort_stok === 'low') {
                $query->orderBy('stok', 'asc');
            } elseif ($request->sort_stok === 'high') {
                $query->orderBy('stok', 'desc');
            } else {
                $query->orderBy('id', 'desc');
            }

            $data = $query->paginate(10);

            return response()->json([
                'data' => $data->items(),
                'total' => $data->total(),
                'next_page' => $data->nextPageUrl()
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle Status Aktif/Non-Aktif
     */
    public function toggleStatus($id)
    {
        try {
            $part = Sparepart::findOrFail($id);
            $part->status = !$part->status; // Simpel toggle
            $part->save();

            return response()->json([
                'status' => 'success',
                'message' => "Status berhasil diubah menjadi " . ($part->status ? 'Aktif' : 'Non-Aktif'),
                'new_status' => $part->status
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Simpan atau Update Data
     */
    public function store(Request $request)
    {
        // Bersihkan format ribuan pada input harga
        if ($request->filled('harga_beli')) {
            $request->merge([
                'harga_beli' => str_replace('.', '', $request->harga_beli),
                'harga_jual' => str_replace('.', '', $request->harga_jual),
            ]);
        }

        $request->validate([
            'kode_part'  => 'required|unique:spareparts,kode_part,' . $request->id,
            'nama_part'  => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric|gt:harga_beli',
            'stok'       => 'required|integer|min:0',
            'stok_min' => 'required|numeric',
        ], [
            'harga_jual.gt'    => 'Harga jual harus lebih tinggi dari harga beli.',
            'kode_part.unique' => 'Kode part sudah terdaftar di sistem.',
        ]);

        try {
            $sparepart = Sparepart::updateOrCreate(
                ['id' => $request->id],
                [
                    'kode_part'  => strtoupper($request->kode_part),
                    'nama_part'  => strtoupper($request->nama_part),
                    'kategori'   => strtoupper($request->kategori),
                    'harga_beli' => $request->harga_beli,
                    'harga_jual' => $request->harga_jual,
                    'stok'       => $request->stok,
                    'stok_min'       => $request->stok_min,
                    'rak'        => strtoupper($request->rak),
                    'status'     => $request->status ?? 1,
                ]
            );

            return response()->json([
                'status'  => 'success',
                'message' => $request->id ? 'Data berhasil diperbarui' : 'Data berhasil ditambahkan',
                'data'    => $sparepart
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mutasi Stok (Masuk/Keluar)
     */
    public function mutasiStok(Request $request)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'jenis_mutasi' => 'required|in:masuk,keluar',
            'jumlah'       => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $sparepart = Sparepart::findOrFail($request->sparepart_id);
            $jumlah = (int)$request->jumlah;

            if ($request->jenis_mutasi == 'masuk') {
                $sparepart->increment('stok', $jumlah);
            } else {
                if ($sparepart->stok < $jumlah) {
                    return response()->json(['status' => 'error', 'message' => 'Stok tidak cukup!'], 422);
                }
                $sparepart->decrement('stok', $jumlah);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Stok berhasil diperbarui. Stok sekarang: {$sparepart->stok}"
            ]);
        });
    }

  public function tambahStok(Request $request)
{
    $request->validate([
        'id' => 'required|exists:spareparts,id',
        'jumlah' => 'required|integer|min:1'
    ]);

    try {
        $part = Sparepart::findOrFail($request->id);
        
        // Simpan jumlah yang akan ditambah untuk respon
        $jumlahDitambah = (int)$request->jumlah;
        
        // Update stok di database
        $part->increment('stok', $jumlahDitambah);

        $part->refresh();

        // KUNCI PENTING: Nama array harus sama dengan di JavaScript res.nama, res.jumlah_ditambah, dll
       return response()->json([
            'status' => 'success',
            'nama' => $part->nama_part, // Cek di JS: res.nama atau res.nama_part?
            'nama_part' => $part->nama_part, // Tambahkan ini buat jaga-jaga
            'jumlah' => $jumlahDitambah,
            'jumlah_ditambah' => $jumlahDitambah,
            'stok_sekarang' => $part->stok 
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error', 
            'message' => 'Gagal: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Monitor Stok Rendah
     */
    public function getLowStock()
{
    // Mengambil data yang stoknya kurang dari atau sama dengan batas minimumnya masing-masing
    $data = Sparepart::where('status', 1)
        ->whereRaw('stok <= stok_min') 
        ->orderBy('stok', 'asc')
        ->get();

    return response()->json($data);
}
    /**
     * Hapus Data
     */
    public function destroy($id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
