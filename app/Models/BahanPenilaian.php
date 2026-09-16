<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanPenilaian extends Model
{
    protected $table = 'bahan_penilaian';

    protected $fillable = [
        'nama_bahan',
        'link',
        'keterangan',
    ];
}