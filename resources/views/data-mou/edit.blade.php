@extends('layouts.app')

@section('title', 'Edit Data MOU')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Data MOU</h3>
    <a href="{{ route('data-mou.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('data-mou.update', $mou->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="no_sk" class="form-label">No. SK <span class="text-danger">*</span></label>
                    <input type="text" name="no_sk" id="no_sk" class="form-control"
                           value="{{ old('no_sk', $mou->no_sk) }}" maxlength="255" required>
                </div>
                <div class="col-md-3">
                    <label for="no_tambahan" class="form-label">No. Tambahan</label>
                    <input type="text" name="no_tambahan" id="no_tambahan" class="form-control"
                           value="{{ old('no_tambahan', $mou->no_tambahan) }}" maxlength="255">
                </div>
                <div class="col-md-3">
                    <label for="status_kepegawaian" class="form-label">Status Kepegawaian</label>
                    <select name="status_kepegawaian" id="status_kepegawaian" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach($statusList as $status)
                            <option value="{{ $status->nama_status }}" @selected(old('status_kepegawaian', $mou->status_kepegawaian) === $status->nama_status)>{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="status_detail" class="form-label">Status Detail</label>
                    <input type="text" name="status_detail" id="status_detail" class="form-control"
                           value="{{ old('status_detail', $mou->status_detail) }}" maxlength="255" placeholder="PKWTT">
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control"
                           value="{{ old('nama', $mou->nama) }}" maxlength="255" required>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-3">
                    <label for="gelar" class="form-label">Gelar</label>
                    <input type="text" name="gelar" id="gelar" class="form-control"
                           value="{{ old('gelar', $mou->gelar) }}" maxlength="255" placeholder="S.Pd.">
                </div>
                <div class="col-md-3">
                    <label for="hari_kerja" class="form-label">Hari Kerja</label>
                    <input type="text" name="hari_kerja" id="hari_kerja" class="form-control"
                           value="{{ old('hari_kerja', $mou->hari_kerja) }}" maxlength="255" placeholder="Senin-Jumat">
                </div>
                <div class="col-md-3">
                    <label for="jam_kerja" class="form-label">Jam Kerja</label>
                    <input type="text" name="jam_kerja" id="jam_kerja" class="form-control"
                           value="{{ old('jam_kerja', $mou->jam_kerja) }}" maxlength="255" placeholder="08.00-16.00">
                </div>
                <div class="col-md-3">
                    <label for="hari" class="form-label">Hari MOU</label>
                    <input type="text" name="hari" id="hari" class="form-control"
                           value="{{ old('hari', $mou->hari) }}" maxlength="255" placeholder="Senin">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label for="tgl_mou" class="form-label">Tanggal MOU</label>
                    <input type="text" name="tgl_mou" id="tgl_mou" class="form-control"
                           value="{{ old('tgl_mou', $mou->tgl_mou) }}" placeholder="dd/mm/YYYY">
                </div>
                <div class="col-md-4">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                           value="{{ old('tempat_lahir', $mou->tempat_lahir) }}" maxlength="255">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir', $mou->tanggal_lahir?->format('d/m/Y')) }}" placeholder="dd/mm/YYYY">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                    <input type="text" name="unit_kerja" id="unit_kerja" class="form-control"
                           value="{{ old('unit_kerja', $mou->unit_kerja) }}" maxlength="255" placeholder="Yayasan">
                </div>
                <div class="col-md-6">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2" class="form-control"
                              placeholder="Alamat lengkap">{{ old('alamat', $mou->alamat) }}</textarea>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                    <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control"
                           value="{{ old('gaji_pokok', $mou->gaji_pokok) }}" placeholder="Rp 3.000.000">
                </div>
                <div class="col-md-4">
                    <label for="tunjangan_jabatan" class="form-label">Tunjangan Jabatan</label>
                    <input type="text" name="tunjangan_jabatan" id="tunjangan_jabatan" class="form-control"
                           value="{{ old('tunjangan_jabatan', $mou->tunjangan_jabatan) }}" placeholder="Rp 500.000">
                </div>
                <div class="col-md-4">
                    <label for="tunjangan_transport" class="form-label">Tunjangan Transport</label>
                    <input type="text" name="tunjangan_transport" id="tunjangan_transport" class="form-control"
                           value="{{ old('tunjangan_transport', $mou->tunjangan_transport) }}" placeholder="Rp 300.000">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label for="tunjangan_kinerja" class="form-label">Tunjangan Kinerja</label>
                    <input type="text" name="tunjangan_kinerja" id="tunjangan_kinerja" class="form-control"
                           value="{{ old('tunjangan_kinerja', $mou->tunjangan_kinerja) }}" placeholder="Rp 200.000">
                </div>
                <div class="col-md-4">
                    <label for="tunjangan_fungsional" class="form-label">Tunjangan Fungsional</label>
                    <input type="text" name="tunjangan_fungsional" id="tunjangan_fungsional" class="form-control"
                           value="{{ old('tunjangan_fungsional', $mou->tunjangan_fungsional) }}" placeholder="Rp 100.000">
                </div>
                <div class="col-md-4">
                    <label for="thp" class="form-label">THP</label>
                    <input type="text" name="thp" id="thp" class="form-control"
                           value="{{ old('thp', $mou->thp) }}" placeholder="Rp 4.400.000">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="terbilang" class="form-label">Terbilang</label>
                    <input type="text" name="terbilang" id="terbilang" class="form-control"
                           value="{{ old('terbilang', $mou->terbilang) }}" maxlength="255">
                </div>
                <div class="col-md-3">
                    <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="text" name="tgl_mulai" id="tgl_mulai" class="form-control"
                           value="{{ old('tgl_mulai', $mou->tgl_mulai?->format('d/m/Y')) }}" placeholder="dd/mm/YYYY">
                </div>
                <div class="col-md-3">
                    <label for="berlaku" class="form-label">Berlaku</label>
                    <input type="text" name="berlaku" id="berlaku" class="form-control"
                           value="{{ old('berlaku', $mou->berlaku) }}" maxlength="255" placeholder="01/09/2026">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                           value="{{ old('tanggal_akhir', $mou->tanggal_akhir?->format('d/m/Y')) }}" placeholder="dd/mm/YYYY">
                </div>
                <div class="col-md-3">
                    <label for="saksi1" class="form-label">Saksi 1</label>
                    <input type="text" name="saksi1" id="saksi1" class="form-control"
                           value="{{ old('saksi1', $mou->saksi1) }}" maxlength="255">
                </div>
                <div class="col-md-3">
                    <label for="saksi2" class="form-label">Saksi 2</label>
                    <input type="text" name="saksi2" id="saksi2" class="form-control"
                           value="{{ old('saksi2', $mou->saksi2) }}" maxlength="255">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('data-mou.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
