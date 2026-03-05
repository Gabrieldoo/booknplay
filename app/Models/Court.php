<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = [
        'nama_lapangan',
        'jenis_olahraga',
        'harga_per_jam',
    ];
}
