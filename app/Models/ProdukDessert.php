<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukDessert extends Model
{
    protected $table = 'produk_dessert';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_dessert',
        'harga',
        'stok',
        'deskripsi',
    ];
}
