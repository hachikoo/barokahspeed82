<?php

namespace App\Http\Controllers;

use App\Models\{Transaksi, Sparepart, Mekanik, Jasa, Unit, Kendaraan, Konsumen};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonsumenController extends Controller
{
    /**
     * Menampilkan daftar konsumen.
     * Menggunakan unique('no_wa') agar tidak ada duplikasi tampilan jika satu No WA punya banyak kendaraan.
     */
    public function index()
    {
        $konsumens = Konsumen::with(['kendaraans.unit'])
            ->orderBy('id', 'desc')
            ->get()
            ->unique('no_wa')
            ->values();

        return view('master.konsumen.index', compact('konsumens'));
    }

    /**
     * Menyimpan data konsumen baru.
     * Sesuai struktur DB: nama_konsumen, no_wa, alamat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_konsumen' => 'required',
            'no_wa'         => 'required|unique:konsumens,no_wa',
            'alamat'        => 'nullable'
        ]);

        $konsumen = Konsumen::create([
            'nama_konsumen' => strtoupper($request->nama_konsumen),
            'no_wa'         => $request->no_wa,
            'alamat'        => strtoupper($request->alamat),
        ]);

        // WAJIB: Return JSON agar AJAX menangkap pesan success
        return response()->json([
            'status' => 'success',
            'message' => 'Pelanggan baru berhasil terdaftar!'
        ]);
    }

    public function getData(Request $request)
    {
        $query = Konsumen::with(['kendaraans.unit']); // Eager load agar tidak lambat

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', "%{$search}%")
                    ->orWhere('no_wa', 'like', "%{$search}%")
                    ->orWhereHas('kendaraans', function ($k) use ($search) {
                        $k->where('no_polisi', 'like', "%{$search}%");
                    });
            });
        }

        $data = $query->orderBy('nama_konsumen', 'asc')->paginate(15);
        return response()->json($data);
    }
    /**
     * Mengambil riwayat servis berdasarkan ID Kendaraan (via AJAX).
     */
    public function getRiwayatKendaraan($kendaraan_id)
    {
        $riwayat = Transaksi::with(['details'])
            ->where('kendaraan_id', $kendaraan_id)
            ->latest()
            ->get();

        if ($riwayat->isEmpty()) {
            return '<div class="text-center py-4 text-muted small">Belum ada riwayat servis untuk unit ini.</div>';
        }

        return view('master.konsumen._riwayat_table', compact('riwayat'))->render();
    }



    /**
     * Update data konsumen.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_consumer' => 'nullable', // Sesuaikan dengan nama field di DB
                'nama_konsumen' => 'required|string|max:255',
                'no_wa'         => 'required',
                'alamat'        => 'nullable',
            ]);

            $konsumen = Konsumen::findOrFail($id);
            $konsumen->update([
                'nama_konsumen' => strtoupper($request->nama_konsumen),
                'no_wa'         => $request->no_wa,
                'alamat'        => strtoupper($request->alamat),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data konsumen berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus konsumen beserta seluruh riwayat terkait (Cascading Delete manual).
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $konsumen = Konsumen::findOrFail($id);

            // 1. Hapus detail transaksi dari semua transaksi milik konsumen ini
            DB::table('transaksi_details')
                ->whereIn('transaksi_id', function ($query) use ($id) {
                    $query->select('id')->from('transaksis')->where('konsumen_id', $id);
                })->delete();

            // 2. Hapus data transaksi
            DB::table('transaksis')->where('konsumen_id', $id)->delete();

            // 3. Hapus kendaraan milik konsumen
            DB::table('kendaraans')->where('konsumen_id', $id)->delete();

            // 4. Hapus data utama konsumen
            $konsumen->delete();

            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Data konsumen dan seluruh riwayat servis berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
