@extends('layouts.app')

@section('title', 'Tambah Data SK')

@section('content')

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('data-sk.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="no_sk" class="form-label">No SK</label>
                    <input type="text" name="no_sk" id="no_sk" class="form-control" value="{{ old('no_sk') }}">
                </div>
                <div class="col-md-6">
                    <label for="no_tambahan" class="form-label">No Tambahan</label>
                    <input type="text" name="no_tambahan" id="no_tambahan" class="form-control" value="{{ old('no_tambahan') }}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="gelar" class="form-label">Gelar</label>
                    <input type="text" name="gelar" id="gelar" class="form-control" value="{{ old('gelar') }}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                </div>
                <div class="col-md-6">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" placeholder="dd/mm/YYYY">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="nipy" class="form-label">NIPY</label>
                    <input type="text" name="nipy" id="nipy" class="form-control" value="{{ old('nipy') }}">
                </div>
                <div class="col-md-6">
                    <label for="gol_ruang" class="form-label">Gol Ruang</label>
                    <input type="text" name="gol_ruang" id="gol_ruang" class="form-control" value="{{ old('gol_ruang') }}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="status_kepegawaian" class="form-label">Status Kepegawaian</label>
                    <select name="status_kepegawaian" id="status_kepegawaian" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach($statusList as $status)
                            <option value="{{ $status->nama_status }}" @selected(old('status_kepegawaian') === $status->nama_status)>{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                    <input type="text" name="unit_kerja" id="unit_kerja" class="form-control" value="{{ old('unit_kerja') }}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="tmt" class="form-label">TMT</label>
                    <input type="text" name="tmt" id="tmt" class="form-control" value="{{ old('tmt') }}">
                </div>
                <div class="col-md-6">
                    <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="text" name="tgl_mulai" id="tgl_mulai" class="form-control" value="{{ old('tgl_mulai') }}" placeholder="dd/mm/YYYY">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label for="berlaku" class="form-label">Berlaku (bulan)</label>
                    <input type="number" name="berlaku" id="berlaku" class="form-control" value="{{ old('berlaku') }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir') }}" placeholder="dd/mm/YYYY">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_ditetapkan" class="form-label">Tanggal Ditetapkan</label>
                    <input type="text" name="tanggal_ditetapkan" id="tanggal_ditetapkan" class="form-control" value="{{ old('tanggal_ditetapkan') }}" placeholder="dd/mm/YYYY">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('data-sk.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
