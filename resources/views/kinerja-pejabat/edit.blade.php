@extends('layouts.app')

@section('title', 'Edit Pejabat Penilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Pejabat Penilai</h3>
    <a href="{{ route('kinerja-pejabat.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('kinerja-pejabat.update', $pejabat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Pegawai</label>
                <input type="text" class="form-control" value="{{ $pejabat->pegawai?->nama ?? $pejabat->nama }}" disabled>
                <small class="text-muted">Data pegawai tidak dapat diubah dari halaman ini.</small>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" id="nama" maxlength="100"
                       class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $pejabat->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" name="jabatan" id="jabatan" maxlength="50"
                       class="form-control @error('jabatan') is-invalid @enderror"
                       value="{{ old('jabatan', $pejabat->jabatan) }}">
                @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <input type="text" name="unit" id="unit" maxlength="20"
                       class="form-control @error('unit') is-invalid @enderror"
                       value="{{ old('unit', $pejabat->unit) }}">
                @error('unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Pilih Status --</option>
                    @foreach($statuses as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('status', $pejabat->status) === (string)$nilai)>{{ $label }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Data status diambil dari master Status Kepegawaian (kinerja-status).</small>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                          class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pejabat->keterangan) }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection