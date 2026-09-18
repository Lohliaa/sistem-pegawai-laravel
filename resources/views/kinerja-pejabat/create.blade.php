@extends('layouts.app')

@section('title', 'Tambah Pejabat Penilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Pejabat Penilai</h3>
    <a href="{{ route('kinerja-pejabat.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        @if($pegawais->isEmpty())
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i>
                Tidak ada pegawai dengan role Kanit atau Kabid yang belum terdaftar sebagai pejabat penilai.
            </div>
        @else
            <form action="{{ route('kinerja-pejabat.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="pegawai_id" class="form-label">Pegawai (Kanit / Kabid) <span class="text-danger">*</span></label>
                    <select name="pegawai_id" id="pegawai_id" class="form-select @error('pegawai_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" @selected((int) old('pegawai_id') === $pegawai->id)>
                                {{ $pegawai->nama }}{{ $pegawai->user ? ' (Role: '.strtoupper($pegawai->user->role).')' : '' }}{{ $pegawai->jabatan ? ' - '.$pegawai->jabatan : '' }}{{ $pegawai->unit ? ' ('.$pegawai->unit.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hanya menampilkan pegawai dengan role Kanit atau Kabid. Nama, jabatan, dan unit akan disalin otomatis.</small>
                    @error('pegawai_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach($statuses as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('status') === (string)$nilai)>{{ $label }}</option>
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
                              class="form-control @error('keterangan') is-invalid @enderror"
                              placeholder="Contoh: Penilai utama bidang SDM">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </form>
        @endif
    </div>
</div>
@endsection