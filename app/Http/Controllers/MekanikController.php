<?php

namespace App\Http\Controllers;

use App\Models\Mekanik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MekanikController extends Controller
{
    public function index()
    {
        // Hanya tampilkan yang aktif di tabel utama
        $mekaniks = Mekanik::where('status', 'aktif')
            ->orderBy('nama_mekanik', 'asc')
            ->get();
        return view('master.mekanik.index', compact('mekaniks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mekanik' => 'required|string|max:255',
            'whatsapp'     => 'required', // numeric validasi dilepas jika ada masking karakter
            'alamat'       => 'nullable',
            'status'       => 'required|in:aktif,tidak aktif',
        ]);

        try {
            Mekanik::create([
                'nama_mekanik' => strtoupper($request->nama_mekanik), // Double check uppercase
                'whatsapp'     => $request->whatsapp,
                'alamat'       => $request->alamat,
                'status'       => $request->status,
            ]);

            return response()->json(['success' => 'Data mekanik berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function getData(Request $request)
    {
        $query = Mekanik::query();

        // Logika Pencarian
        if ($request->search) {
            $query->where('nama_mekanik', 'LIKE', "%{$request->search}%")
                ->orWhere('whatsapp', 'LIKE', "%{$request->search}%");
        }

        // Logika Filter Status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Menggunakan paginate agar support infinite scroll (default 10-15 data per load)
        $mekaniks = $query->latest()->paginate(15);

        return response()->json($mekaniks);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mekanik' => 'required|string|max:255',
            'whatsapp'     => 'required',
            'alamat'       => 'nullable',
            'status'       => 'required|in:aktif,tidak aktif',
        ]);

        try {
            $mekanik = Mekanik::findOrFail($id);
            $mekanik->update([
                'nama_mekanik' => strtoupper($request->nama_mekanik),
                'whatsapp'     => $request->whatsapp,
                'alamat'       => $request->alamat,
                'status'       => $request->status,
            ]);

            return response()->json(['success' => 'Data mekanik berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui data'], 500);
        }
    }

    public function destroy($id)
    {
        $mekanik = Mekanik::findOrFail($id);
        $mekanik->delete(); // Jika pakai SoftDeletes, data tetap ada di DB tapi deleted_at terisi

        return response()->json([
            'status' => 'success',
            'message' => 'Data mekanik berhasil dihapus'
        ]);
    }
}
