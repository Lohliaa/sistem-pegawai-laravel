@extends('layouts.app')

@section('title', 'Laporan Penilaian')

@section('content')

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('laporan-penilaian.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Pegawai</label>
                <select name="pegawai_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" @selected(($filters['pegawai_id'] ?? '') === $pegawai->id)>{{ $pegawai->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Periode</label>
                <select name="periode_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($periodes as $periode)
                        <option value="{{ $periode->id }}" @selected(($filters['periode_id'] ?? '') === $periode->id)>{{ $periode->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Pejabat Penilai</label>
                <select name="pejabat_penilai_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($pejabats as $pejabat)
                        <option value="{{ $pejabat->id }}" @selected(($filters['pejabat_penilai_id'] ?? '') === $pejabat->id)>{{ $pejabat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Terapkan</button>
                <a href="{{ route('laporan-penilaian.cetak-semua', request()->query()) }}" target="_blank" class="btn btn-warning" 
                   @if(auth()->check() && (auth()->user()->role === 'kanit' || auth()->user()->role === 'kabid'))
                       @php
                           $userPejabat = \App\Models\PejabatPenilai::where('pegawai_id', auth()->user()->pegawai_id)->first();
                       @endphp
                       @if(!$userPejabat)
                           style="pointer-events: none; opacity: 0.5;"
                       @endif
                   @endif
                >
                    <i class="bi bi-printer"></i> Print Semua
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Pegawai</th>
                    <th>Jabatan</th>
                    <th>Unit</th>
                    <th>Pejabat Penilai</th>
                    <th>Periode</th>
                    <th class="text-end" width="110">Nilai Total</th>
                    <th width="120">Predikat</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $index }}</td>
                    <td>{{ $laporan->pegawai?->nama ?? '-' }}</td>
                    <td>{{ $laporan->pegawai?->jabatan ?? '-' }}</td>
                    <td>{{ $laporan->pegawai?->unit ?? '-' }}</td>
                    <td>{{ $laporan->pejabatPenilai?->nama ?? '-' }}</td>
                    <td>{{ $laporan->periode?->label ?? '-' }}</td>
                    <td class="text-end">{{ $laporan->nilai_total !== null ? number_format((float) $laporan->nilai_total, 2, ',', '.') : '-' }}</td>
                    <td>{{ $laporan->predikat ?: '-' }}</td>
                    <td>
                        <a href="{{ route('laporan-penilaian.detail', $laporan->id) }}" class="btn btn-sm btn-info" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('print.penilaian', $laporan->id) }}" target="_blank" class="btn btn-sm btn-success" title="Print">
                            <i class="bi bi-printer"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada data laporan penilaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $laporans->links() }}
    </div>
</div>
@endsection