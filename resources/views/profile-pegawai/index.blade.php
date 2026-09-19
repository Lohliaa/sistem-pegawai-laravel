@extends('layouts.app')

@section('title', 'Profile Pegawai')

@section('content')

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('profile-pegawai.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Nama / Unit / Jabatan">
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" title="Terapkan"><i class="bi bi-search"></i></button>
                    <a href="{{ route('profile-pegawai.create') }}" class="btn btn-success" title="Tambah Pegawai">
                        <i class="bi bi-plus-circle"></i>
                    </a>
                </div>
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
                    <th width="150">Aksi</th>
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
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('profile-pegawai.show', $pegawai->id) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('profile-pegawai.edit', $pegawai->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('profile-pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                        onclick="return confirm('Hapus pegawai {{ $pegawai->nama }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data pegawai</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pegawais->links() }}
    </div>
</div>
@endsection