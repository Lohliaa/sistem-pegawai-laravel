@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')

@include('partials.errors')

<form action="{{ route('profile-pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <ul class="nav nav-tabs mb-4" id="pegawaiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab">Data Pribadi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="kepegawaian-tab" data-bs-toggle="tab" data-bs-target="#kepegawaian" type="button" role="tab">Kepegawaian</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pendidikan-tab" data-bs-toggle="tab" data-bs-target="#pendidikan" type="button" role="tab">Pendidikan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab">Dokumen</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab" data-bs-target="#tambahan" type="button" role="tab">Lainnya</button>
        </li>
    </ul>

    <div class="tab-content" id="pegawaiTabContent">
        <!-- Tab Data Pribadi -->
        <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pegawai->nama) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="foto" class="form-label">Foto Profil @if($pegawai->foto)<small class="text-muted">(Saat ini: <a href="{{ asset('storage/'.$pegawai->foto) }}" target="_blank">Lihat</a>)</small>@endif</label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik', $pegawai->nik) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="nomor_kk" class="form-label">Nomor KK</label>
                            <input type="text" name="nomor_kk" id="nomor_kk" class="form-control" value="{{ old('nomor_kk', $pegawai->nomor_kk) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="tempat" class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat" id="tempat" class="form-control" value="{{ old('tempat', $pegawai->tempat) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="L" @selected(old('gender', $pegawai->gender) === 'L')>Laki-laki</option>
                                <option value="P" @selected(old('gender', $pegawai->gender) === 'P')>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
                            <select name="status_pernikahan" id="status_pernikahan" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="Lajang" @selected(old('status_pernikahan', $pegawai->status_pernikahan) === 'Lajang')>Lajang</option>
                                <option value="Menikah" @selected(old('status_pernikahan', $pegawai->status_pernikahan) === 'Menikah')>Menikah</option>
                                <option value="Cerai" @selected(old('status_pernikahan', $pegawai->status_pernikahan) === 'Cerai')>Cerai</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $pegawai->no_hp) }}">
                        </div>
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $pegawai->email) }}">
                        </div>
                        <div class="col-md-12">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="2" class="form-control">{{ old('alamat', $pegawai->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Kepegawaian -->
        <div class="tab-pane fade" id="kepegawaian" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="unit" class="form-label">Unit</label>
                            <select name="unit" id="unit" class="form-select">
                                <option value="">-- Pilih Unit --</option>
                                @foreach(['Daycare', 'TPA', 'Preschool', 'TKIT', 'TKIP', 'MI', 'SDIT', 'SMPIT', 'MA', 'PKBM', 'Yayasan'] as $unitOption)
                                    <option value="{{ $unitOption }}" @selected(old('unit', $pegawai->unit) === $unitOption)>{{ $unitOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan', $pegawai->jabatan) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="jenis_tenaga" class="form-label">Jenis Tenaga</label>
                            <input type="text" name="jenis_tenaga" id="jenis_tenaga" class="form-control" value="{{ old('jenis_tenaga', $pegawai->jenis_tenaga) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status_kepegawaian_id" class="form-label">Status Kepegawaian</label>
                            <select name="status_kepegawaian_id" id="status_kepegawaian_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach($statusList as $st)
                                    <option value="{{ $st->id }}" @selected(old('status_kepegawaian_id', $pegawai->status_kepegawaian_id) == $st->id)>{{ $st->nama_status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_tmt" class="form-label">Tanggal TMT</label>
                            <input type="date" name="tanggal_tmt" id="tanggal_tmt" class="form-control" value="{{ old('tanggal_tmt', $pegawai->tanggal_tmt?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="golongan_ruang" class="form-label">Golongan/Ruang</label>
                            <input type="text" name="golongan_ruang" id="golongan_ruang" class="form-control" value="{{ old('golongan_ruang', $pegawai->golongan_ruang) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="atasan_langsung" class="form-label">Atasan Langsung</label>
                            <input type="text" name="atasan_langsung" id="atasan_langsung" class="form-control" value="{{ old('atasan_langsung', $pegawai->atasan_langsung) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="status_aktif" class="form-label">Status Aktif</label>
                            <select name="status_aktif" id="status_aktif" class="form-select">
                                @foreach(['Aktif', 'Resign', 'Pensiun', 'PHK', 'Meninggal', 'Pensiun Dini'] as $statusOption)
                                    <option value="{{ $statusOption }}" @selected(old('status_aktif', $pegawai->status_aktif) === $statusOption)>{{ $statusOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nomor_sk" class="form-label">Nomor SK</label>
                            <input type="text" name="nomor_sk" id="nomor_sk" class="form-control" value="{{ old('nomor_sk', $pegawai->nomor_sk) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_sk" class="form-label">Tanggal SK</label>
                            <input type="date" name="tanggal_sk" id="tanggal_sk" class="form-control" value="{{ old('tanggal_sk', $pegawai->tanggal_sk?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="masa_kerja" class="form-label">Masa Kerja</label>
                            <input type="text" name="masa_kerja" id="masa_kerja" class="form-control" value="{{ old('masa_kerja', $pegawai->masa_kerja) }}" placeholder="Misal: 5 Tahun 2 Bulan">
                        </div>
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">Hubungkan Akun Sistem</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">-- Tanpa Akun --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id', $pegawai->user_id) == $user->id)>{{ $user->username }} ({{ strtoupper($user->role) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Pendidikan -->
        <div class="tab-pane fade" id="pendidikan" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) }}" placeholder="Contoh: S1, S2">
                        </div>
                        <div class="col-md-6">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" id="jurusan" class="form-control" value="{{ old('jurusan', $pegawai->jurusan) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="institusi" class="form-label">Institusi</label>
                            <input type="text" name="institusi" id="institusi" class="form-control" value="{{ old('institusi', $pegawai->institusi) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                            <input type="text" name="tahun_lulus" id="tahun_lulus" class="form-control" value="{{ old('tahun_lulus', $pegawai->tahun_lulus) }}" maxlength="4">
                        </div>
                        <div class="col-md-12">
                            <label for="sertifikasi" class="form-label">Sertifikasi</label>
                            <textarea name="sertifikasi" id="sertifikasi" rows="3" class="form-control">{{ old('sertifikasi', $pegawai->sertifikasi) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="pelatihan" class="form-label">Pelatihan</label>
                            <textarea name="pelatihan" id="pelatihan" rows="3" class="form-control">{{ old('pelatihan', $pegawai->pelatihan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Dokumen -->
        <div class="tab-pane fade" id="dokumen" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        @php
                            $docs = [
                                'dokumen_ktp' => 'Dokumen KTP',
                                'dokumen_kk' => 'Dokumen KK',
                                'dokumen_ijazah' => 'Dokumen Ijazah',
                                'dokumen_sk' => 'Dokumen SK',
                                'dokumen_mou' => 'Dokumen MoU',
                                'dokumen_sk_jabatan' => 'Dokumen SK Jabatan',
                                'dokumen_skck' => 'Dokumen SKCK',
                                'dokumen_sertifikat' => 'Dokumen Sertifikat',
                            ];
                        @endphp
                        @foreach($docs as $key => $label)
                        <div class="col-md-6">
                            <label for="{{ $key }}" class="form-label">{{ $label }} @if($pegawai->$key)<small class="text-muted">(<a href="{{ asset('storage/'.$pegawai->$key) }}" target="_blank">Lihat</a>)</small>@endif</label>
                            <input type="file" name="{{ $key }}" id="{{ $key }}" class="form-control">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Lainnya -->
        <div class="tab-pane fade" id="tambahan" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="data_bpi" class="form-label">Data BPI</label>
                            <select name="data_bpi" id="data_bpi" class="form-select">
                                <option value="">-- Pilih Data BPI --</option>
                                @foreach(['Jenjang Dasar', 'Menengah', 'Lanjutan'] as $bpiOption)
                                    <option value="{{ $bpiOption }}" @selected(old('data_bpi', $pegawai->data_bpi) === $bpiOption)>{{ $bpiOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="data_presensi" class="form-label">Data Presensi</label>
                            <textarea name="data_presensi" id="data_presensi" rows="2" class="form-control">{{ old('data_presensi', $pegawai->data_presensi) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="data_cuti" class="form-label">Data Cuti</label>
                            <textarea name="data_cuti" id="data_cuti" rows="2" class="form-control">{{ old('data_cuti', $pegawai->data_cuti) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="riwayat_jabatan" class="form-label">Riwayat Jabatan</label>
                            <textarea name="riwayat_jabatan" id="riwayat_jabatan" rows="2" class="form-control">{{ old('riwayat_jabatan', $pegawai->riwayat_jabatan) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="riwayat_mutasi" class="form-label">Riwayat Mutasi</label>
                            <textarea name="riwayat_mutasi" id="riwayat_mutasi" rows="2" class="form-control">{{ old('riwayat_mutasi', $pegawai->riwayat_mutasi) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="riwayat_status_kepegawaian" class="form-label">Riwayat Status Kepegawaian</label>
                            <textarea name="riwayat_status_kepegawaian" id="riwayat_status_kepegawaian" rows="2" class="form-control">{{ old('riwayat_status_kepegawaian', $pegawai->riwayat_status_kepegawaian) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 pb-5">
        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Perbarui Data Pegawai</button>
        <a href="{{ route('profile-pegawai.index') }}" class="btn btn-secondary btn-lg">Batal</a>
    </div>
</form>
@endsection