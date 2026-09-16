@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Pegawai</h3>
    <div>
        <a href="{{ route('profile-pegawai.export', $pegawai->id) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Excel
        </a>
        <a href="{{ route('profile-pegawai.edit', $pegawai->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('profile-pegawai.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card mb-3">
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">ID</th>
                <td>{{ $pegawai->id }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $pegawai->nama }}</td>
            </tr>
            <tr>
                <th>Tempat / Tanggal Lahir</th>
                <td>{{ $pegawai->tempat ?: '-' }}{{ $pegawai->tanggal_lahir ? ', '.$pegawai->tanggal_lahir->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $pegawai->gender === 'L' ? 'Laki-laki' : ($pegawai->gender === 'P' ? 'Perempuan' : '-') }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $pegawai->alamat ?: '-' }}</td>
            </tr>
            <tr>
                <th>Unit</th>
                <td>{{ $pegawai->unit ?: '-' }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>{{ $pegawai->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal TMT</th>
                <td>{{ $pegawai->tanggal_tmt?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Akun User</th>
                <td>
                    @if($pegawai->user)
                        {{ $pegawai->user->username }} ({{ \App\Models\User::ROLES[$pegawai->user->role] ?? $pegawai->user->role }})
                    @else
                        <span class="text-muted">Belum terhubung</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $pegawai->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $pegawai->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

@if($pegawai->penilaian->isNotEmpty())
<div class="card">
    <div class="card-header bg-primary text-white">
        <strong><i class="bi bi-clipboard-check"></i> Riwayat Penilaian Kinerja ({{ $pegawai->penilaian->count() }})</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Periode</th>
                    <th>Pejabat Penilai</th>
                    <th width="110">Nilai Total</th>
                    <th width="120">Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->penilaian as $index => $penilaian)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penilaian->periode?->label ?? '-' }}</td>
                    <td>{{ $penilaian->pejabatPenilai?->nama ?: '-' }}</td>
                    <td>{{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2, ',', '.') : '-' }}</td>
                    <td>{{ \App\Models\PenilaianKinerja::STATUSES[$penilaian->status] ?? $penilaian->status }}</td>
                    <td>
                        <a href="{{ route('form-penilaian.show', $penilaian->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada penilaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection