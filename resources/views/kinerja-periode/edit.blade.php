@extends('layouts.app')

@section('title', 'Edit Periode Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Periode Penilaian</h3>
    <a href="{{ route('kinerja-periode.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('kinerja-periode.update', $periode->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama_kuartal" class="form-label">Kuartal <span class="text-danger">*</span></label>
                <select name="nama_kuartal" id="nama_kuartal" class="form-select @error('nama_kuartal') is-invalid @enderror" required>
                    <option value="">-- Pilih Kuartal --</option>
                    @foreach($kuartal as $kode)
                        <option value="{{ $kode }}" @selected(old('nama_kuartal', $periode->nama_kuartal) === $kode)>{{ $kode }}</option>
                    @endforeach
                </select>
                @error('nama_kuartal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="periode_bulan" class="form-label">Periode Bulan <span class="text-danger">*</span></label>
                <input type="text" name="periode_bulan" id="periode_bulan"
                       class="form-control @error('periode_bulan') is-invalid @enderror"
                       value="{{ old('periode_bulan', $periode->periode_bulan) }}" required>
                @error('periode_bulan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="number" name="tahun" id="tahun" min="2000" max="2100"
                       class="form-control @error('tahun') is-invalid @enderror"
                       value="{{ old('tahun', $periode->tahun) }}" required>
                @error('tahun')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection