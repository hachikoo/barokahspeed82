<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sparepart extends Model
{
    protected $fillable = [
        'kode_part',
        'nama_part',
        'kategori',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_min',
        'status',
        'rak'
    ];

    protected $casts = [
        'status' => 'integer',
        'harga_beli' => 'integer',
        'harga_jual' => 'integer',
    ];
    public function stok()
    {
        return $this->hasOne(Stok::class);
    }
}
