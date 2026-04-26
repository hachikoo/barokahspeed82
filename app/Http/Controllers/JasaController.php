<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JasaController extends Controller
{
    /**
     * Tampilkan Halaman Utama Master Jasa
     */
    public function index()
    {
        return view('master.jasa.index');
    }

    /**
     * Ambil Data untuk Infinite Scroll & Search (AJAX)
     */
    public function getData(Request $request)
    {
        $search = $request->search;

        $data = Jasa::when($search, function ($query) use ($search) {
            return $query->where('nama_jasa', 'like', "%$search%");
        })
            ->orderBy('nama_jasa', 'asc')
            ->paginate(20);

        return response()->json($data);
    }

    /**
     * Simpan Data Jasa Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jasa'  => 'required|string|max:255',
            'biaya_jasa' => 'required|numeric',
        ]);

        try {
            Jasa::create([
                'nama_jasa'  => strtoupper($request->nama_jasa),
                'biaya_jasa' => $request->biaya_jasa,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Layanan jasa berhasil didaftarkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Data Jasa
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jasa'  => 'required|string|max:255',
            'biaya_jasa' => 'required|numeric',
        ]);

        try {
            $jasa = Jasa::findOrFail($id);
            $jasa->update([
                'nama_jasa'  => strtoupper($request->nama_jasa),
                'biaya_jasa' => $request->biaya_jasa,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data jasa berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus Data Jasa
     */
    public function destroy($id)
    {
        try {
            $jasa = Jasa::findOrFail($id);
            $jasa->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Layanan jasa berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
