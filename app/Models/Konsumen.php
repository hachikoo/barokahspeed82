<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    protected $fillable = ['nama_konsumen', 'no_wa', 'alamat'];

    // Tambahkan ini agar error di Dashboard hilang
    // App\Models\Konsumen.php
    public function kendaraans()
    {
        // Konsumen bisa punya banyak kendaraan (history)
        return $this->hasMany(Kendaraan::class, 'konsumen_id');
    }
}
