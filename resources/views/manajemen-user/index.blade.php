@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-people"></i> Manajemen User</h3>
        <small class="text-muted">Kelola akun pengguna sistem.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('manajemen-user.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Excel
        </a>
        <a href="{{ route('manajemen-user.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah User
        </a>
    </div>
</div>
<hr>

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('manajemen-user.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Username / Nama Pegawai">
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach(\App\Models\User::ROLES as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('role') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Terapkan</button>
                <a href="{{ route('manajemen-user.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nama Pegawai</th>
                    <th>Unit</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</td>
                    <td>{{ $user->pegawai?->nama ?? '-' }}</td>
                    <td>{{ $user->pegawai?->unit ?? '-' }}</td>
                    <td>
                        <a href="{{ route('manajemen-user.show', $user->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('manajemen-user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('manajemen-user.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus user {{ $user->username }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>
@endsection