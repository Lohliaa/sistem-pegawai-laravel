@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('manajemen-user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pegawai</label>
                <input type="text" name="nama" id="nama" class="form-control"
                    value="{{ old('nama', $user->pegawai?->nama) }}" maxlength="255" placeholder="Masukkan nama pegawai">
                <small class="text-muted">Nama profil pegawai yang terhubung dengan akun ini.</small>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" id="username" class="form-control"
                    value="{{ old('username', $user->username) }}" maxlength="50" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control" minlength="6">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti password. Minimal 6 karakter.</small>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    @foreach($roles as $nilai => $label)
                    <option value="{{ $nilai }}" @selected(old('role', $user->role) === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
            <a href="{{ route('manajemen-user.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection