<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'rating',
        'jumlah_ulasan',
        'kategori',
        'gambar',
        'stok',
    ];
}
