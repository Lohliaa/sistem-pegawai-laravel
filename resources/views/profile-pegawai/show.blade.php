@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Pegawai</h3>
    <div>
        <a href="{{ route('profile-pegawai.edit', $pegawai->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('profile-pegawai.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="row">
    <!-- Kolom Kiri: Foto & Informasi Utama -->
    <div class="col-md-4">
        <div class="card mb-4 text-center">
            <div class="card-body">
                @if($pegawai->foto)
                    <img src="{{ asset('storage/'.$pegawai->foto) }}" class="img-fluid rounded mb-3" style="max-height: 300px;">
                @else
                    <div class="bg-light rounded mb-3 py-5">
                        <i class="bi bi-person-circle display-1 text-secondary"></i>
                    </div>
                @endif
                <h4 class="mb-0">{{ $pegawai->nama }}</h4>
                <p class="text-muted">{{ $pegawai->jabatan ?? '-' }}</p>
                <div class="badge bg-primary">{{ $pegawai->unit ?? '-' }}</div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <strong><i class="bi bi-person-gear"></i> Akun Sistem</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr>
                        <th width="150" class="ps-3">Username</th>
                        <td>{{ $pegawai->user?->username ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Role</th>
                        <td>{{ $pegawai->user ? strtoupper($pegawai->user->role) : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Status Akun</th>
                        <td><span class="badge bg-success">Aktif</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Detail Data -->
    <div class="col-md-8">
        <!-- Placeholder untuk Detail Data -->
        <div id="detail-data-container">
        <!-- Data Pribadi -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong><i class="bi bi-person"></i> Data Pribadi</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr>
                        <th width="200" class="ps-3">Nama Lengkap</th>
                        <td>{{ $pegawai->nama }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">NIK</th>
                        <td>{{ $pegawai->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Nomor KK</th>
                        <td>{{ $pegawai->nomor_kk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Tempat/Tgl Lahir</th>
                        <td>{{ $pegawai->tempat ?? '-' }}{{ $pegawai->tanggal_lahir ? ', '.$pegawai->tanggal_lahir->format('d/m/Y') : '' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Jenis Kelamin</th>
                        <td>{{ $pegawai->gender === 'L' ? 'Laki-laki' : ($pegawai->gender === 'P' ? 'Perempuan' : '-') }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Status Pernikahan</th>
                        <td>{{ $pegawai->status_pernikahan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Alamat</th>
                        <td>{{ $pegawai->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">No. HP</th>
                        <td>{{ $pegawai->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Email</th>
                        <td>{{ $pegawai->email ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Data Kepegawaian -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <strong><i class="bi bi-briefcase"></i> Data Kepegawaian</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr>
                        <th width="200" class="ps-3">Unit</th>
                        <td>{{ $pegawai->unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Jabatan</th>
                        <td>{{ $pegawai->jabatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Nama Bidang <small class="text-muted">(khusus unit yayasan)</small></th>
                        <td>{{ $pegawai->nama_bidang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Status Kepegawaian</th>
                        <td>{{ $pegawai->statusKepegawaian?->nama_status ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Tanggal TMT</th>
                        <td>{{ $pegawai->tanggal_tmt ? $pegawai->tanggal_tmt->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Golongan/Ruang</th>
                        <td>{{ $pegawai->golongan_ruang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Atasan Langsung</th>
                        <td>{{ $pegawai->atasan_langsung ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Nomor SK</th>
                        <td>{{ $pegawai->nomor_sk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Tanggal SK</th>
                        <td>{{ $pegawai->tanggal_sk ? $pegawai->tanggal_sk->format('d/m/Y') : '-' }}</td>
        <!-- Pendidikan & Kompetensi -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <strong><i class="bi bi-mortarboard"></i> Pendidikan & Kompetensi</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr>
                        <th width="200" class="ps-3">Pendidikan Terakhir</th>
                        <td>{{ $pegawai->pendidikan_terakhir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Jurusan</th>
                        <td>{{ $pegawai->jurusan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Institusi</th>
                        <td>{{ $pegawai->institusi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Tahun Lulus</th>
                        <td>{{ $pegawai->tahun_lulus ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Sertifikasi</th>
                        <td>{!! nl2br(e($pegawai->sertifikasi)) ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Pelatihan</th>
                        <td>{!! nl2br(e($pegawai->pelatihan)) ?: '-' !!}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <strong><i class="bi bi-file-earmark-text"></i> Dokumen</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    @php
                        $docs = [
                            'dokumen_ktp' => 'KTP',
                            'dokumen_kk' => 'KK',
                            'dokumen_ijazah' => 'Ijazah',
                            'dokumen_sk' => 'SK',
                            'dokumen_mou' => 'MoU',
                            'dokumen_sk_jabatan' => 'SK Jabatan',
                            'dokumen_skck' => 'SKCK',
                            'dokumen_sertifikat' => 'Sertifikat',
                        ];
                    @endphp
                    @foreach($docs as $key => $label)
                    <tr>
                        <th width="200" class="ps-3">{{ $label }}</th>
                        <td>
                            @if($pegawai->$key)
                                <a href="{{ asset('storage/'.$pegawai->$key) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Lihat/Unduh
                                </a>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <!-- Data Tambahan -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <strong><i class="bi bi-plus-square"></i> Data Tambahan</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr>
                        <th width="200" class="ps-3">Data BPI</th>
                        <td>{!! nl2br(e($pegawai->data_bpi)) ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Data Presensi</th>
                        <td>{!! nl2br(e($pegawai->data_presensi)) ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Data Cuti</th>
                        <td>{!! nl2br(e($pegawai->data_cuti)) ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Riwayat Jabatan</th>
                        <td>{!! nl2br(e($pegawai->riwayat_jabatan)) ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Riwayat Mutasi</th>
                        <td>{!! nl2br(e($pegawai->riwayat_mutasi)) ?: '-' !!}</td>
                    </tr>
                    <tr>
                        <th class="ps-3">Riwayat Status Kepegawaian</th>
                        <td>{!! nl2br(e($pegawai->riwayat_status_kepegawaian)) ?: '-' !!}</td>
                    </tr>
                </table>
            </div>
        </div>

                @if($pegawai->penilaian->isNotEmpty())
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <strong><i class="bi bi-clipboard-check"></i> Penilaian Kinerja ({{ $pegawai->penilaian->count() }})</strong>
            </div>
            <div class="card-body">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">No</th>
                            <th>Periode</th>
                            <th>Pejabat Penilai</th>
                            <th width="110">Nilai Total</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pegawai->penilaian as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->periode?->label ?? '-' }}</td>
                            <td>{{ $item->pejabatPenilai?->nama ?: '-' }}</td>
                            <td>{{ $item->nilai_total !== null ? number_format((float) $item->nilai_total, 2, ',', '.') : '-' }}</td>
                            <td>
                                <a href="{{ route('form-penilaian.show', $item->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
