<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $fillable = [
        'konsumen_id',
        'unit_id',   // <--- PASTIKAN INI ADA
        'no_polisi',
        'merk_tipe',
    ];
    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class);
    }
    public function unit()
    {
        // Setiap data kendaraan merujuk ke satu tipe di tabel units
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
