<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units'; // Nama tabel di database
    // App\Models\Unit.php

    protected $fillable = ['nama_unit', 'merk']; // Tambahkan 'merk' di sini
}
