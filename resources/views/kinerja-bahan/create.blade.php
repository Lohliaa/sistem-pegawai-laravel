@extends('layouts.app')

@section('title', 'Tambah Bahan Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Bahan Penilaian</h3>
    <a href="{{ route('kinerja-bahan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('kinerja-bahan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama_bahan" class="form-label">Nama Bahan <span class="text-danger">*</span></label>
                <input type="text" name="nama_bahan" id="nama_bahan" maxlength="100"
                       class="form-control @error('nama_bahan') is-invalid @enderror"
                       value="{{ old('nama_bahan') }}" placeholder="Contoh: SKP 2026" required>
                @error('nama_bahan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">Link <span class="text-danger">*</span></label>
                <input type="url" name="link" id="link" maxlength="255"
                       class="form-control @error('link') is-invalid @enderror"
                       value="{{ old('link') }}" placeholder="https://..." required>
                @error('link')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                          class="form-control @error('keterangan') is-invalid @enderror"
                          placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection