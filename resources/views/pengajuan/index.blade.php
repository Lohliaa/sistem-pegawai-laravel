@extends('layouts.app')

@section('title', 'Daftar Pengajuan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Pengajuan</h2>
    @if(auth()->user()->role == 'staf')
    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Pengajuan Baru
    </a>
    @endif
</div>
<hr>

@if(auth()->user()->role == 'admin')
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('pengajuan.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $filters['nama'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Unit</label>
                <select name="unit" class="form-select">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}" @selected(($filters['unit'] ?? '') == $unit)>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['status'] ?? '') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary" title="Filter"><i class="bi bi-search"></i></button>
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                <a href="{{ route('pengajuan.export', $filters) }}" class="btn btn-success" title="Download Excel"><i class="bi bi-file-earmark-excel"></i></a>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tipe</th>
                    <th>Nama</th>
                    <th>Unit</th>
                    <th>Pimpinan Atasan</th>
                    <th>TMT</th>
                    <th>Status</th>
                    @if(auth()->user()->role == 'admin')
                    <th>Dibuat Oleh</th>
                    @endif
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $p->tipe_pengajuan }}</span></td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ $p->pimpinan_atasan }}</td>
                    <td>{{ $p->tanggal_tmt->format('d/m/Y') }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($p->status == 'approved_kanit')
                            <span class="badge bg-info">Approved Kanit</span>
                        @elseif($p->status == 'approved_kabid')
                            <span class="badge bg-success">Approved Kabid</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    @if(auth()->user()->role == 'admin')
                    <td>{{ $p->creator->username ?? '-' }}</td>
                    @endif
                    <td>
                        <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        @if((auth()->user()->role == 'staf' && $p->status == 'pending' && $p->created_by == auth()->id()) || auth()->user()->role == 'admin')
                        <form action="{{ route('pengajuan.destroy', $p->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengajuan?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role == 'admin' ? 9 : 8 }}" class="text-center">Tidak ada data pengajuan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
