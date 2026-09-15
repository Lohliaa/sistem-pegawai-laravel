@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2>Dashboard</h2>
<hr>

<!-- Identitas Diri -->
<div class="card mb-4 shadow-sm" style="border-left: 4px solid #3498db;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Identitas Diri - {{ auth()->user()->username }}</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <strong>Username</strong><br>
                {{ auth()->user()->username }}
            </div>
            <div class="col-md-4">
                <strong>Role</strong><br>
                <span class="badge bg-info">{{ strtoupper(auth()->user()->role) }}</span>
            </div>
            @if(isset($identity->nama))
            <div class="col-md-4">
                <strong>Nama</strong><br>
                {{ $identity->nama }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Role-based Dashboard Content -->
@if($role == 'admin')
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Pengajuan</h5>
                    <h2>{{ $data['total_pengajuan'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2>{{ $data['pending'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Approved</h5>
                    <h2>{{ $data['approved'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ditolak</h5>
                    <h2>{{ $data['rejected'] ?? 0 }}</h2>
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
