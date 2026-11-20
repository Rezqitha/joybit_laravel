<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlasanProduk extends Model
{
    protected $table = 'ulasan_produk';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_pembeli',
        'id_produk',
        'komentar',
        'rating',
    ];
}
