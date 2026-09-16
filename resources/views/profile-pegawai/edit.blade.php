@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Pegawai</h3>
    <a href="{{ route('profile-pegawai.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('profile-pegawai.update', $pegawai->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="user_id" class="form-label">Akun User</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">-- Tanpa Akun --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $pegawai->user_id) === $user->id)>{{ $user->username }} ({{ \App\Models\User::ROLES[$user->role] ?? $user->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control"
                           value="{{ old('nama', $pegawai->nama) }}" maxlength="255" required>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="tempat" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat" id="tempat" class="form-control"
                           value="{{ old('tempat', $pegawai->tempat) }}" maxlength="255">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" @selected(old('gender', $pegawai->gender) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $pegawai->gender) === 'P')>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control"
                          placeholder="Alamat lengkap">{{ old('alamat', $pegawai->alamat) }}</textarea>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="unit" class="form-label">Unit</label>
                    <input type="text" name="unit" id="unit" class="form-control"
                           value="{{ old('unit', $pegawai->unit) }}" maxlength="255">
                </div>
                <div class="col-md-6">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan" class="form-control"
                           value="{{ old('jabatan', $pegawai->jabatan) }}" maxlength="255" placeholder="Contoh: Guru, Kabid SDM">
                </div>
            </div>

            <div class="mt-3">
                <label for="tanggal_tmt" class="form-label">Tanggal TMT</label>
                <input type="date" name="tanggal_tmt" id="tanggal_tmt" class="form-control"
                       value="{{ old('tanggal_tmt', $pegawai->tanggal_tmt?->format('Y-m-d')) }}">
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('profile-pegawai.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection