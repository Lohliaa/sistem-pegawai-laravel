@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')

@section('content')
<h2>Buat Pengajuan Baru</h2>
<hr>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pengajuan.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipe_pengajuan" class="form-label">Tipe Pengajuan *</label>
                    <select name="tipe_pengajuan" id="tipe_pengajuan" class="form-select @error('tipe_pengajuan') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="MOU" {{ old('tipe_pengajuan') == 'MOU' ? 'selected' : '' }}>MOU</option>
                        <option value="SK" {{ old('tipe_pengajuan') == 'SK' ? 'selected' : '' }}>SK</option>
                    </select>
                    @error('tipe_pengajuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama *</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tanggal_tmt" class="form-label">Tanggal TMT *</label>
                    <input type="date" name="tanggal_tmt" id="tanggal_tmt" class="form-control @error('tanggal_tmt') is-invalid @enderror" value="{{ old('tanggal_tmt') }}" required>
                    @error('tanggal_tmt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit *</label>
                    <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                        <option value="">-- Pilih Unit --</option>
                        <option value="Daycare Permata">Daycare Permata</option>
                        <option value="TPA Permata">TPA Permata</option>
                        <option value="KBIT Permata">KBIT Permata</option>
                        <option value="TKIT Permata">TKIT Permata</option>
                        <option value="TKIP Permata">TKIP Permata</option>
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Pengajuan</button>
            </div>
        </form>
    </div>
</div>

@endsection
