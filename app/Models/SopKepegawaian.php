<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopKepegawaian extends Model
{
    protected $table = 'sop_kepegawaian';

    protected $fillable = [
        'judul_sop',
        'dokumen',
        'terakhir_diperbarui',
    ];

    protected $casts = [
        'terakhir_diperbarui' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
