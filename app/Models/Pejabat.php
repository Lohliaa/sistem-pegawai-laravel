<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    protected $table = 'pejabat';
    
    protected $fillable = [
        'nama',
        'jabatan',
    ];
}
