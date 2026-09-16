@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Penilaian Kinerja</h3>
    <div>
        @if($penilaian->isEditable())
        <a href="{{ route('form-penilaian.edit', $penilaian->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        @endif
        <a href="{{ route('form-penilaian.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <strong>{{ $penilaian->pegawai?->nama ?? '-' }}</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">Pegawai</th>
                <td>{{ $penilaian->pegawai?->nama ?: '-' }}</td>
            </tr>
            <tr>
                <th>Unit / Jabatan</th>
                <td>{{ $penilaian->pegawai?->unit ?: '-' }} / {{ $penilaian->pegawai?->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Periode</th>
                <td>{{ $penilaian->periode?->label ?? '-' }}</td>
            </tr>
            <tr>
                <th>Pejabat Penilai</th>
                <td>{{ $penilaian->pejabatPenilai?->nama ?: '-' }} ({{ $penilaian->pejabatPenilai?->jabatan ?: '-' }})</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @php
                        $badge = match($penilaian->status) {
                            'draft' => 'bg-secondary',
                            'submitted' => 'bg-warning',
                            'approved' => 'bg-success',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badge }}">{{ \App\Models\PenilaianKinerja::STATUSES[$penilaian->status] ?? $penilaian->status }}</span>
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $penilaian->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $penilaian->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <strong><i class="bi bi-stars"></i> Nilai Aspek Penilaian</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="60">Kode</th>
                    <th>Aspek</th>
                    <th width="100">Nilai</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aspek as $kolom => $info)
                <tr>
                    <td>{{ $info['kode'] }}</td>
                    <td>{{ $info['label'] }}</td>
                    <td class="text-center">{{ $penilaian->{$kolom} ?? '-' }}</td>
                    <td>
                        @php
                            $nilai = $penilaian->{$kolom};
                            $deskripsi = match($nilai) {
                                4 => 'Sangat Baik',
                                3 => 'Baik',
                                2 => 'Cukup',
                                1 => 'Kurang',
                                0 => 'Kurang',
                                default => '-',
                            };
                        @endphp
                        {{ $deskripsi }}
                    </td>
                </tr>
                @endforeach
                <tr class="table-active">
                    <th colspan="2">Nilai Total</th>
                    <th class="text-center">{{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2, ',', '.') : '-' }}</th>
                    <td>{{ $penilaian->predikat }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@if($penilaian->catatan)
<div class="card mt-3">
    <div class="card-header bg-warning text-dark">
        <strong><i class="bi bi-journal"></i> Catatan</strong>
    </div>
    <div class="card-body mb-0">
        {{ $penilaian->catatan }}
    </div>
</div>
@endif
@endsection