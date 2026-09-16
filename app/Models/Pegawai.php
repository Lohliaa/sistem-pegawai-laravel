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

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pejabatPenilai()
    {
        return $this->hasOne(PejabatPenilai::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianKinerja::class);
    }
}

