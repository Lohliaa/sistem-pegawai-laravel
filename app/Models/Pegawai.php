<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    
    protected $fillable = [
        'user_id',
        'nama',
        'tempat',
        'tanggal_lahir',
        'gender',
        'alamat',
        'unit',
        'jabatan',
        'tanggal_tmt',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_tmt' => 'date',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

