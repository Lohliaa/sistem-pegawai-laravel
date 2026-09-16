@extends('layouts.app')

@section('title', 'Detail Bahan Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Bahan Penilaian</h3>
    <div>
        <a href="{{ route('kinerja-bahan.edit', $bahan->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('kinerja-bahan.index') }}" class="btn btn-secondary">
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
                <td>{{ $bahan->id }}</td>
            </tr>
            <tr>
                <th>Nama Bahan</th>
                <td>{{ $bahan->nama_bahan }}</td>
            </tr>
            <tr>
                <th>Link</th>
                <td><a href="{{ $bahan->link }}" target="_blank" rel="noopener">{{ $bahan->link }}</a></td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $bahan->keterangan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $bahan->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $bahan->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection