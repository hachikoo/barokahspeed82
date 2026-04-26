<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Konsumen;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('konsumen')->get();
        // Pastikan menggunakan :: (double colon) untuk memanggil method static Model
        $konsumens = Konsumen::all();
        return view('master.kendaraan.index', compact('kendaraans', 'konsumens'));
    }

    public function store(Request $request)
    {
        // Pastikan menggunakan -> (tanda panah) untuk $request, bukan titik
        Kendaraan::create($request->all());
        return redirect()->back()->with('success', 'Unit kendaraan berhasil didaftarkan!');
    }
}
