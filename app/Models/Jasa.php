<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    protected $table = 'jasas'; // Sesuai gambar PHPMyAdmin Mas
    protected $fillable = ['nama_jasa', 'biaya_jasa']; // Gunakan 'biaya_jasa' bukan 'harga'

    // Tambahkan baris ini agar Laravel tidak mencari created_at & updated_at
    public $timestamps = false;
}
