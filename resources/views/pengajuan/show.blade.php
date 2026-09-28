@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('content')
<h2>Detail Pengajuan</h2>
<hr>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informasi Pengajuan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>Tipe:</strong> <span class="badge bg-secondary">{{ $pengajuan->tipe_pengajuan }}</span>
            </div>
            <div class="col-md-6 mb-3">
                <strong>Status:</strong>
                @if($pengajuan->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($pengajuan->status == 'approved_kanit')
                    <span class="badge bg-info">Approved Kanit</span>
                @elseif($pengajuan->status == 'approved_kabid')
                    <span class="badge bg-success">Approved Kabid</span>
                @else
                    <span class="badge bg-danger">Ditolak</span>
                @endif
            </div>
            <div class="col-md-6 mb-3"><strong>Nama:</strong> {{ $pengajuan->nama }}</div>
            <div class="col-md-6 mb-3"><strong>Unit:</strong> {{ $pengajuan->unit }}</div>
            <div class="col-md-6 mb-3"><strong>Pimpinan Atasan:</strong> {{ $pengajuan->pimpinan_atasan }}</div>
            <div class="col-md-6 mb-3"><strong>Tanggal Lahir:</strong> {{ $pengajuan->tanggal_lahir->format('d/m/Y') }}</div>
            <div class="col-md-6 mb-3"><strong>TMT:</strong> {{ $pengajuan->tanggal_tmt->format('d/m/Y') }}</div>
            @if($pengajuan->file_pengajuan)
            <div class="col-md-12 mb-3">
                <strong>File Lampiran:</strong>
                <div class="mt-1">
                    <a href="{{ asset('storage/' . $pengajuan->file_pengajuan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-text"></i> Lihat / Unduh File Lampiran
                    </a>
                </div>
            </div>
            @endif
            @if($pengajuan->keterangan)
            <div class="col-md-12 mb-3"><strong>Keterangan:</strong> {{ $pengajuan->keterangan }}</div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
        @if(auth()->user()->role == 'kanit' && $pengajuan->status == 'pending')
        <form action="{{ route('pengajuan.approve', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <div class="mb-2">
                <label>Catatan (Opsional):</label>
                <textarea name="catatan" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Setujui</button>
        </form>
        <form action="{{ route('pengajuan.reject', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <div class="mb-2">
                <label>Alasan Penolakan:</label>
                <textarea name="alasan" class="form-control" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?')">Tolak</button>
        </form>
        @endif
        @if(auth()->user()->role == 'kabid' && ($pengajuan->status == 'pending' || $pengajuan->status == 'approved_kanit'))
        <form action="{{ route('pengajuan.approve', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <div class="mb-2">
                <label>Catatan (Opsional):</label>
                <textarea name="catatan" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Setujui</button>
        </form>
        <form action="{{ route('pengajuan.reject', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <div class="mb-2">
                <label>Alasan Penolakan:</label>
                <textarea name="alasan" class="form-control" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?')">Tolak</button>
        </form>
        @endif
        @if(auth()->user()->role == 'admin')
        <hr class="my-3">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="text-primary fw-bold">Admin Actions</h6>
                <form action="{{ route('pengajuan.process', $pengajuan->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Catatan Admin:</label>
                        <textarea name="catatan_admin" class="form-control" rows="2">{{ $pengajuan->catatan_admin }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Proses (Set Processed)</button>
                </form>
                <form action="{{ route('pengajuan.complete', $pengajuan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Selesaikan (Set Completed)</button>
                </form>
            </div>
        </div>
        @endif

<div class="card mt-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Riwayat Proses & Persetujuan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Kanit Process -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-info">
                    <div class="card-header bg-info text-white">Proses Kanit</div>
                    <div class="card-body">
                        @if($pengajuan->approved_by_kanit)
                            <p class="text-success fw-bold mb-1"><i class="bi bi-check-circle"></i> Disetujui</p>
                            <p class="mb-1"><strong>Oleh:</strong> {{ $pengajuan->approverKanit->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $pengajuan->approved_date_kanit ? $pengajuan->approved_date_kanit->format('d/m/Y H:i') : '-' }}</p>
                            <p class="mb-0"><strong>Catatan:</strong> {{ $pengajuan->catatan_kanit ?? '-' }}</p>
                        @elseif($pengajuan->rejected_by_kanit)
                            <p class="text-danger fw-bold mb-1"><i class="bi bi-x-circle"></i> Ditolak</p>
                            <p class="mb-1"><strong>Oleh:</strong> {{ $pengajuan->rejector->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $pengajuan->rejected_date_kanit ? $pengajuan->rejected_date_kanit->format('d/m/Y H:i') : '-' }}</p>
                            <p class="mb-0"><strong>Alasan:</strong> {{ $pengajuan->rejected_reason ?? '-' }}</p>
                        @else
                            <p class="text-muted fst-italic mb-0">Belum diproses / Menunggu Kanit</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kabid Process -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-success">
                    <div class="card-header bg-success text-white">Proses Kabid</div>
                    <div class="card-body">
                        @if($pengajuan->approved_by_kabid)
                            <p class="text-success fw-bold mb-1"><i class="bi bi-check-circle"></i> Disetujui Final</p>
                            <p class="mb-1"><strong>Oleh:</strong> {{ $pengajuan->approverKabid->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $pengajuan->approved_date_kabid ? $pengajuan->approved_date_kabid->format('d/m/Y H:i') : '-' }}</p>
                            <p class="mb-0"><strong>Catatan:</strong> {{ $pengajuan->catatan_kabid ?? '-' }}</p>
                        @else
                            <p class="text-muted fst-italic mb-0">Belum diproses / Menunggu Kabid</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Admin Process -->
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-primary">
                    <div class="card-header bg-primary text-white">Proses Admin</div>
                    <div class="card-body">
                        @if($pengajuan->processed_by || $pengajuan->completed_by)
                            @if($pengajuan->processed_by)
                                <p class="mb-1"><strong>Diproses Oleh:</strong> {{ $pengajuan->processor->name ?? '-' }}</p>
                                <p class="mb-1"><strong>Tanggal Proses:</strong> {{ $pengajuan->processed_date ? $pengajuan->processed_date->format('d/m/Y H:i') : '-' }}</p>
                            @endif
                            @if($pengajuan->completed_by)
                                <p class="mb-1"><strong>Diselesaikan Oleh:</strong> {{ $pengajuan->completer->name ?? '-' }}</p>
                                <p class="mb-1"><strong>Tanggal Selesai:</strong> {{ $pengajuan->completed_date ? $pengajuan->completed_date->format('d/m/Y H:i') : '-' }}</p>
                            @endif
                            <p class="mb-0"><strong>Catatan Admin:</strong> {{ $pengajuan->catatan_admin ?? '-' }}</p>
                        @else
                            <p class="text-muted fst-italic mb-0">Belum diproses oleh Admin</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>
@endsection

