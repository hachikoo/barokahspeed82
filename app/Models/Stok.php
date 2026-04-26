<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    // Cek di phpMyAdmin, apakah nama tabelnya 'stoks' atau 'stok'?
    // Sesuaikan baris di bawah ini dengan nama yang ada di phpMyAdmin
    protected $table = 'stoks';

    protected $fillable = ['sparepart_id', 'jumlah_stok'];
}
