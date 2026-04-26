<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'no_polisi',
        'no_faktur',
        'konsumen_id',
        'kendaraan_id',
        'mekanik_id',
        'total_harga',
        'bayar', // Tambahkan ini
        'kembali',
    ];



    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class);
    }

    public function mekanik()
    {
        return $this->belongsTo(Mekanik::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
