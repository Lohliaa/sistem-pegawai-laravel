@extends('layouts.app')

@section('title', 'Detail Pejabat Penilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Pejabat Penilai</h3>
    <div>
        <a href="{{ route('kinerja-pejabat.edit', $pejabat->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('kinerja-pejabat.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card mb-3">
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">Nama</th>
                <td>{{ $pejabat->nama }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>{{ $pejabat->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Unit</th>
                <td>{{ $pejabat->unit ?: '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($pejabat->status_aktif === \App\Models\PejabatPenilai::STATUS_AKTIF)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $pejabat->keterangan ?: '-' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <strong><i class="bi bi-clipboard-check"></i> Riwayat Penilaian ({{ $pejabat->penilaian->count() }})</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Pegawai</th>
                    <th>Periode</th>
                    <th width="110">Nilai Total</th>
                    <th width="120">Status</th>
                    <th width="240">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pejabat->penilaian as $index => $penilaian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penilaian->pegawai?->nama ?? '-' }}</td>
                    <td>{{ $penilaian->periode?->label ?? '-' }}</td>
                    <td>{{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2) : '-' }}</td>
                    <td>{{ \App\Models\PenilaianKinerja::STATUSES[$penilaian->status] ?? $penilaian->status }}</td>
                    <td>
                        <a href="{{ route('form-penilaian.show', $penilaian->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada penilaian oleh pejabat ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection