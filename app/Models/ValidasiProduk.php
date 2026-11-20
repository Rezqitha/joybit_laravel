<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidasiProduk extends Model
{
    protected $table = 'validasi_prdouk';
    protected $primaryKey = 'id_validasi';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'nama_toko',
        'status',
        'deskripsi',
        'dibuat_pada',
    ];
}
