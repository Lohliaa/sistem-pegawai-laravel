<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusKepegawaian extends Model
{
    protected $table = 'status_kepegawaian';

    protected $fillable = [
        'nama_status',
    ];
}