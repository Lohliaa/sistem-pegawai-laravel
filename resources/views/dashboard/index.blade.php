@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
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
                        <img src="{{ asset('storage/'.$identity->foto) }}" alt="Foto" class="img-thumbnail mb-2" style="max-width: 150px; max-height: 180px; object-fit: contain;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 120px; height: 120px; font-size: 3rem;">
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
    @include('dashboard.partials.pegawai-statistik', ['data' => $data])


@endsection
