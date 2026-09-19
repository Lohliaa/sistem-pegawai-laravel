@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('manajemen-user.store') }}" method="POST">
            @csrf
             <div class="mb-3" id="group_nama">
                <label for="nama" class="form-label">Nama Pegawai Baru (Jika belum ada)</label>
                <input type="text" name="nama" id="nama" class="form-control"
                       value="{{ old('nama') }}" maxlength="255">
                <small class="text-muted">Gunakan ini jika ingin membuat profil pegawai baru secara otomatis.</small>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" id="username" class="form-control"
                       value="{{ old('username') }}" maxlength="50" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control"
                       minlength="6" required>
                <small class="text-muted">Minimal 6 karakter.</small>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('role') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('manajemen-user.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('pegawai_id').addEventListener('change', function() {
        const groupNama = document.getElementById('group_nama');
        const inputNama = document.getElementById('nama');
        if (this.value) {
            inputNama.value = '';
            groupNama.style.display = 'none';
        } else {
            groupNama.style.display = 'block';
        }
    });

    // Trigger on load
    if (document.getElementById('pegawai_id').value) {
        document.getElementById('group_nama').style.display = 'none';
    }
</script>
@endpush
@endsection