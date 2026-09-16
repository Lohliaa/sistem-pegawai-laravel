@extends('layouts.app')

@section('title', 'Detail Periode Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Periode Penilaian</h3>
    <div>
        <a href="{{ route('kinerja-periode.edit', $periode->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('kinerja-periode.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">ID</th>
                <td>{{ $periode->id }}</td>
            </tr>
            <tr>
                <th>Kuartal</th>
                <td><span class="badge bg-primary">{{ $periode->nama_kuartal }}</span></td>
            </tr>
            <tr>
                <th>Periode Bulan</th>
                <td>{{ $periode->periode_bulan }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $periode->tahun }}</td>
            </tr>
            <tr>
                <th>Label Lengkap</th>
                <td>{{ $periode->label }}</td>
            </tr>
            <tr>
                <th>Jumlah Penilaian</th>
                <td>{{ $periode->penilaian_count }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $periode->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $periode->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection