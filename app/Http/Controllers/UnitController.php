<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UnitController extends Controller
{

    public function storeUnitAjax(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required',
            'merk'      => 'required'
        ]);

        // Simpan ke database barokahspeed.units
        $unit = Unit::create([
            'nama_unit' => strtoupper($request->nama_unit),
            'merk'      => strtoupper($request->merk)
        ]);

        // Kembalikan data lengkap termasuk ID
        return response()->json([
            'success' => true,
            'data'    => $unit
        ]);
    }

    /**
     * Menampilkan halaman utama master unit.
     */
    public function index()
    {
        // Menggunakan method chaining yang lebih terbaca
        $units = Unit::orderBy('merk', 'asc')->get();

        // Pluck langsung memberikan array merk unik
        $merks = Unit::distinct()->orderBy('merk', 'asc')->pluck('merk');

        return view('master.unit.index', compact('units', 'merks'));
    }

    /**
     * Mengambil data unit untuk kebutuhan DataTables atau Infinite Scroll (AJAX).
     */
    public function getData(Request $request)
    {
        try {
            $query = Unit::query();

            // Filter pencarian
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_unit', 'like', "%{$request->search}%")
                        ->orWhere('merk', 'like', "%{$request->search}%");
                });
            }

            // Filter berdasarkan merk
            if ($request->filled('merk')) {
                $query->where('merk', $request->merk);
            }

            $units = $query->orderBy('merk', 'asc')->paginate(10);

            return response()->json($units);
        } catch (Exception $e) {
            Log::error("Error getData Unit: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data'], 500);
        }
    }

    /**
     * Menyimpan unit baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_unit' => ['required', 'string', 'max:100'],
                'merk'      => ['required', 'string', 'max:20'],
            ]);

            $unit = Unit::create([
                'nama_unit' => strtoupper($validated['nama_unit']),
                'merk'      => strtoupper($validated['merk']),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Unit baru berhasil ditambahkan!',
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Memperbarui data unit (AJAX).
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:100'],
            'merk'      => ['required', 'string', 'max:20'],
        ]);

        try {
            $unit = Unit::findOrFail($id);
            $unit->update([
                'nama_unit' => strtoupper($validated['nama_unit']),
                'merk'      => strtoupper($validated['merk']),
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data unit berhasil diperbarui'
            ]);
        } catch (Exception $e) {
            Log::error("Error update Unit ID {$id}: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data'
            ], 500);
        }
    }

    /**
     * Menghapus data unit (AJAX).
     */
    public function destroy($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Tipe unit berhasil dihapus!'
            ]);
        } catch (Exception $e) {
            Log::error("Error destroy Unit ID {$id}: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
