<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stoks';
    protected $fillable = ['sparepart_id', 'tipe', 'qty'];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
