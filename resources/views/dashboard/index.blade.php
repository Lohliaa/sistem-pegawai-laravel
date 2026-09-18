@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard</h2>
<hr>

<!-- Detail Profile Pegawai -->
<div class="card mb-4 shadow-sm" style="border-left: 4px solid #3498db;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Detail Profil Pegawai</h5>
    </div>
    <div class="card-body">
        @if(isset($identity->id) && $identity instanceof \App\Models\Pegawai)
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    @if($identity->foto)
                        <img src="{{ asset('storage/'.$identity->foto) }}" alt="Foto" class="img-thumbnail rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($identity->nama, 0, 1)) }}
                        </div>
                    @endif
                    <h5 class="mb-0">{{ $identity->nama }}</h5>
                    <p class="text-muted small mb-0">{{ $identity->jabatan ?? '-' }}</p>
                </div>
                <div class="col-md-9">
                    <ul class="nav nav-tabs mb-3" id="dashPegawaiTab" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dp-pribadi" type="button">Pribadi</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#dp-kepegawaian" type="button">Kepegawaian</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#dp-pendidikan" type="button">Pendidikan</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#dp-dokumen" type="button">Dokumen</button></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="dp-pribadi">
                            <table class="table table-sm table-borderless">
                                <tr><th width="30%">NIK</th><td>: {{ $identity->nik ?? '-' }}</td></tr>
                                <tr><th>Tempat, Tgl Lahir</th><td>: {{ $identity->tempat ?? '-' }}, {{ $identity->tanggal_lahir?->format('d-m-Y') ?? '-' }}</td></tr>
                                <tr><th>Jenis Kelamin</th><td>: {{ $identity->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                <tr><th>Alamat</th><td>: {{ $identity->alamat ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="dp-kepegawaian">
                            <table class="table table-sm table-borderless">
                                <tr><th width="30%">Unit</th><td>: {{ $identity->unit ?? '-' }}</td></tr>
                                <tr><th>Jabatan</th><td>: {{ $identity->jabatan ?? '-' }}</td></tr>
                                <tr><th>Status</th><td>: {{ $identity->statusKepegawaian?->nama_status ?? '-' }}</td></tr>
                                <tr><th>TMT</th><td>: {{ $identity->tanggal_tmt?->format('d-m-Y') ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="dp-pendidikan">
                            <table class="table table-sm table-borderless">
                                <tr><th width="30%">Pendidikan</th><td>: {{ $identity->pendidikan_terakhir ?? '-' }}</td></tr>
                                <tr><th>Jurusan/Inst</th><td>: {{ $identity->jurusan ?? '-' }} / {{ $identity->institusi ?? '-' }}</td></tr>
                                <tr><th>Tahun Lulus</th><td>: {{ $identity->tahun_lulus ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="dp-dokumen">
                            <div class="row">
                                <div class="col-6"><strong>KTP:</strong> @if($identity->dokumen_ktp)<a href="{{ asset('storage/'.$identity->dokumen_ktp) }}" target="_blank">Lihat</a>@else - @endif</div>
                                <div class="col-6"><strong>SK:</strong> @if($identity->dokumen_sk)<a href="{{ asset('storage/'.$identity->dokumen_sk) }}" target="_blank">Lihat</a>@else - @endif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-muted text-center py-3">Profil pegawai belum dikaitkan dengan akun ini.</p>
        @endif
    </div>
</div>

<!-- Role-based Dashboard Content -->
@if($role == 'admin')
    <div class="row mb-2">
        <div class="col-12">
            <h5 class="text-secondary fw-bold mb-3"><i class="bi bi-file-earmark-text"></i> Ringkasan Pengajuan</h5>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3" style="border-left: 5px solid #0dcaf0 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 rounded me-3" style="background: rgba(13, 202, 240, 0.1);">
                            <i class="bi bi-file-text fs-3 text-info"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Pengajuan</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $data['total_pengajuan'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3" style="border-left: 5px solid #ffc107 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 rounded me-3" style="background: rgba(255, 193, 7, 0.1);">
                            <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Pending</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $data['pending'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3" style="border-left: 5px solid #198754 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 rounded me-3" style="background: rgba(25, 135, 84, 0.1);">
                            <i class="bi bi-check-circle fs-3 text-success"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Approved</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $data['approved'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-3" style="border-left: 5px solid #dc3545 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 rounded me-3" style="background: rgba(220, 53, 69, 0.1);">
                            <i class="bi bi-x-circle fs-3 text-danger"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Ditolak</span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $data['rejected'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <h5 class="text-secondary fw-bold mb-3 mt-2"><i class="bi bi-people"></i> Ringkasan Pegawai & Statistik</h5>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <!-- Total Pegawai -->
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Pegawai</h6>
                            <h2 class="display-6 fw-bold mb-0 mt-2">{{ $data['total_pegawai'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-people display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Jumlah Users</h6>
                            <h2 class="display-6 fw-bold mb-0 mt-2">{{ $data['total_users'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-person-gear display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Jenis Kelamin -->
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Jumlah Jenis Kelamin</h6>
                    <ul class="list-unstyled mb-0 small">
                        @forelse($data['pegawaiPerGender'] ?? [] as $gender => $count)
                            <li class="d-flex justify-content-between py-1 border-bottom border-light border-opacity-25">
                                <span>{{ $gender === 'L' ? 'Laki-laki' : ($gender === 'P' ? 'Perempuan' : 'Tidak Diatur') }}</span>
                                <span class="badge bg-light text-dark fw-bold">{{ $count }}</span>
                            </li>
                        @empty
                            <li>Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Jumlah Data BPI -->
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Jumlah Data BPI</h6>
                    <ul class="list-unstyled mb-0 small">
                        @forelse($data['pegawaiPerBpi'] ?? [] as $bpi => $count)
                            <li class="d-flex justify-content-between py-1 border-bottom border-dark border-opacity-10">
                                <span>{{ $bpi ?: 'Kosong' }}</span>
                                <span class="badge bg-dark text-white fw-bold">{{ $count }}</span>
                            </li>
                        @empty
                            <li>Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Jumlah Pegawai Antar Unit -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-building"></i> Jumlah Pegawai Antar Unit</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerUnit'] ?? [] as $unit => $count)
                                <tr>
                                    <td class="ps-3">{{ $unit ?: 'Tidak Ada Unit' }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jumlah Antar Jabatan -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-briefcase"></i> Jumlah Pegawai Antar Jabatan</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerJabatan'] ?? [] as $jabatan => $count)
                                <tr>
                                    <td class="ps-3">{{ $jabatan ?: 'Tidak Ada Jabatan' }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jumlah Status Kepegawaian -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-card-checklist"></i> Jumlah Status Kepegawaian</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerStatusKepegawaian'] ?? [] as $status => $count)
                                <tr>
                                    <td class="ps-3">{{ $status }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Jumlah Jenjang Kependidikan -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-mortarboard"></i> Jumlah Jenjang Kependidikan</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerPendidikan'] ?? [] as $pendidikan => $count)
                                <tr>
                                    <td class="ps-3">{{ $pendidikan ?: 'Tidak Diisi' }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>


@elseif($role == 'staf')
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pengajuan Saya</h5>
                    <h2>{{ $data['my_pengajuan'] ?? 0 }}</h2>
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm mt-2">Lihat Semua</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2>{{ $data['pending'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <h2>{{ $data['approved'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

@elseif(in_array($role, ['kanit', 'kabid']))
    <div class="row">
        <div class="col-md-6">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Perlu Persetujuan</h5>
                    <h2>{{ $data['need_approval'] ?? 0 }}</h2>
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-light btn-sm mt-2">Lihat & Setujui</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Sudah Disetujui</h5>
                    <h2>{{ $data['approved'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
