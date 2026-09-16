@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Penilaian Kinerja</h3>
    <a href="{{ route('form-penilaian.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('form-penilaian.update', $penilaian->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="pegawai_id" class="form-label">Pegawai <span class="text-danger">*</span></label>
                    <select name="pegawai_id" id="pegawai_id" class="form-select" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" @selected(old('pegawai_id', $penilaian->pegawai_id) === $pegawai->id)>{{ $pegawai->nama }}{{ $pegawai->jabatan ? ' - '.$pegawai->jabatan : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="periode_id" class="form-label">Periode <span class="text-danger">*</span></label>
                    <select name="periode_id" id="periode_id" class="form-select" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}" @selected(old('periode_id', $penilaian->periode_id) === $periode->id)>{{ $periode->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="pejabat_penilai_id" class="form-label">Pejabat Penilai <span class="text-danger">*</span></label>
                <select name="pejabat_penilai_id" id="pejabat_penilai_id" class="form-select" required>
                    <option value="">-- Pilih Pejabat --</option>
                    @foreach($pejabats as $pejabat)
                        <option value="{{ $pejabat->id }}" @selected(old('pejabat_penilai_id', $penilaian->pejabat_penilai_id) === $pejabat->id)>{{ $pejabat->nama }}{{ $pejabat->jabatan ? ' - '.$pejabat->jabatan : '' }}</option>
                    @endforeach
                </select>
            </div>

            <hr>
            <h5 class="mb-3"><i class="bi bi-stars"></i> Nilai Aspek Penilaian (0-4)</h5>

            @foreach($aspek as $kolom => $info)
            <div class="row g-3 align-items-end mb-3">
                <div class="col-md-5">
                    <label for="{{ $kolom }}" class="form-label">{{ $info['label'] }} <span class="text-muted">({{ $info['kode'] }})</span></label>
                </div>
                <div class="col-md-2">
                    <input type="number" name="{{ $kolom }}" id="{{ $kolom }}" class="form-control"
                           min="0" max="4" step="1" value="{{ old($kolom, $penilaian->{$kolom}) }}">
                </div>
                <div class="col-md-5">
                    <select class="form-select" disabled>
                        <option value="0" @selected(($penilaian->{$kolom} ?? 0) === 0)>0 - Kurang</option>
                        <option value="1" @selected(($penilaian->{$kolom} ?? 0) === 1)>1 - Kurang</option>
                        <option value="2" @selected(($penilaian->{$kolom} ?? 0) === 2)>2 - Cukup</option>
                        <option value="3" @selected(($penilaian->{$kolom} ?? 0) === 3)>3 - Baik</option>
                        <option value="4" @selected(($penilaian->{$kolom} ?? 0) === 4)>4 - Sangat Baik</option>
                    </select>
                </div>
            </div>
            @endforeach

            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea name="catatan" id="catatan" rows="3" class="form-control"
                          placeholder="Catatan tambahan (opsional)">{{ old('catatan', $penilaian->catatan) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select" required>
                    @foreach($statuses as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('status', $penilaian->status) === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('form-penilaian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection