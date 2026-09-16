@extends('layouts.app')

@section('title', 'Profile Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-person-badge"></i> Profile Pegawai</h3>
        <small class="text-muted">Daftar data pegawai.</small>
    </div>
    <a href="{{ route('profile-pegawai.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pegawai
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('profile-pegawai.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Nama / Unit / Jabatan / Tempat">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Terapkan</button>
                <a href="{{ route('profile-pegawai.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Unit</th>
                    <th>Jabatan</th>
                    <th width="100">Gender</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawais as $index => $pegawai)
                <tr>
                    <td>{{ $pegawais->firstItem() + $index }}</td>
                    <td>{{ $pegawai->nama }}</td>
                    <td>{{ $pegawai->user?->username ?? '-' }}</td>
                    <td>{{ $pegawai->unit ?: '-' }}</td>
                    <td>{{ $pegawai->jabatan ?: '-' }}</td>
                    <td>{{ $pegawai->gender === 'L' ? 'L' : ($pegawai->gender === 'P' ? 'P' : '-') }}</td>
                    <td>
                        <a href="{{ route('profile-pegawai.show', $pegawai->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('profile-pegawai.edit', $pegawai->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('profile-pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus pegawai {{ $pegawai->nama }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data pegawai</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pegawais->links() }}
    </div>
</div>
@endsection