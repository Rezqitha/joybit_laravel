<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_pembeli',
        'total_harga',
        'status',
        'tanggal',
    ];
}
