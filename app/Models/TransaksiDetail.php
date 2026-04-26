<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Transaksi, Sparepart, Mekanik, Jasa, Unit, Kendaraan, Konsumen};

class TransaksiDetail extends Model
{
    protected $fillable = ['transaksi_id', 'sparepart_id', 'nama_item', 'tipe', 'qty', 'harga_satuan', 'subtotal'];


    public function jasa()
    {
        // Jika nama kolom di DB adalah id_jasa, maka harus ditulis sebagai parameter kedua
        return $this->belongsTo(Jasa::class, 'id_jasa');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }
}
