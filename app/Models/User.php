<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'nama_lengkap',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
