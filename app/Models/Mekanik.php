<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mekanik extends Model
{
    protected $fillable = [
        'nama_mekanik',
        'whatsapp',
        'alamat',
        'status'
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'AKTIF');
    }
}
