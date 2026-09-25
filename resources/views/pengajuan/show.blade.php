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
            <button type="submit" class="btn btn-success">Setujui</button>
        </form>
        <form action="{{ route('pengajuan.reject', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?')">Tolak</button>
        </form>
        @endif
        @if(auth()->user()->role == 'kabid' && ($pengajuan->status == 'pending' || $pengajuan->status == 'approved_kanit'))
        <form action="{{ route('pengajuan.approve', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Setujui</button>
        </form>
        <form action="{{ route('pengajuan.reject', $pengajuan->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?')">Tolak</button>
        </form>
        @endif
    </div>
</div>
@endsection

