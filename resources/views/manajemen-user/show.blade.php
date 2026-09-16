@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail User</h3>
    <div>
        <a href="{{ route('manajemen-user.edit', $user->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('manajemen-user.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">ID</th>
                <td>{{ $user->id }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <th>Role</th>
                <td>{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $user->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

@if($user->pegawai)
<div class="card mt-3">
    <div class="card-header bg-info text-white">
        <strong><i class="bi bi-person-badge"></i> Profil Pegawai</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">Nama</th>
                <td>{{ $user->pegawai->nama }}</td>
            </tr>
            <tr>
                <th>Unit</th>
                <td>{{ $user->pegawai->unit ?: '-' }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>{{ $user->pegawai->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $user->pegawai->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $user->pegawai->gender === 'L' ? 'Laki-laki' : ($user->pegawai->gender === 'P' ? 'Perempuan' : '-') }}</td>
            </tr>
        </table>
    </div>
</div>
@else
<div class="card mt-3">
    <div class="card-body">
        <p class="text-muted mb-0"><i class="bi bi-info-circle"></i> Akun ini belum terhubung dengan data pegawai.</p>
    </div>
</div>
@endif
@endsection