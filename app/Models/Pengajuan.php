<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    
    protected $fillable = [
        'tipe_pengajuan',
        'nama',
        'tanggal_lahir',
        'tanggal_tmt',
        'unit',
        'pimpinan_atasan',
        'keterangan',
        'file_pengajuan',
        'status',
        'created_by',
        'approved_by_kanit',
        'approved_date_kanit',
        'catatan_kanit',
        'rejected_by_kanit',
        'rejected_date_kanit',
        'rejected_reason',
        'approved_by_kabid',
        'approved_date_kabid',
        'catatan_kabid',
        'processed_by',
        'processed_date',
        'completed_by',
        'completed_date',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_tmt' => 'date',
        'approved_date_kanit' => 'datetime',
        'rejected_date_kanit' => 'datetime',
        'approved_date_kabid' => 'datetime',
        'processed_date' => 'datetime',
        'completed_date' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approverKanit()
    {
        return $this->belongsTo(User::class, 'approved_by_kanit');
    }

    public function approverKabid()
    {
        return $this->belongsTo(User::class, 'approved_by_kabid');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by_kanit');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}

