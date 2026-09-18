@extends('layouts.app')

@section('title', 'Form Penilaian - ' . ($kategoriNama ?? 'Pegawai'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-clipboard-check"></i> Form Penilaian - {{ $kategoriNama ?? 'Pegawai' }}</h3>
        <small class="text-muted">Data penilaian kinerja pegawai kategori {{ $kategoriNama ?? 'Pegawai' }}.</small>
    </div>
    <a href="{{ route('form-penilaian.create', ['kategori' => $kategori ?? 'pegawai']) }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Penilaian
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('form-penilaian.index') }}" method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="kategori" value="{{ $kategori ?? 'pegawai' }}">
            <div class="col-md-3">
                <label class="form-label">Pegawai</label>
                <select name="pegawai_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" @selected(($filters['pegawai_id'] ?? '') === $pegawai->id)>{{ $pegawai->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
                <a href="{{ route('form-penilaian.index', ['kategori' => $kategori ?? 'pegawai']) }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th>Pegawai</th>
                    <th>Periode</th>
                    <th>Pejabat Penilai</th>
                    <th class="text-end" width="110">Nilai Total</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penilaians as $index => $penilaian)
                <tr>
                    <td>{{ $penilaians->firstItem() + $index }}</td>
                    <td>{{ $penilaian->pegawai?->nama ?? '-' }}</td>
                    <td>{{ $penilaian->periode?->label ?? '-' }}</td>
                    <td>{{ $penilaian->pejabatPenilai?->nama ?: '-' }}</td>
                    <td class="text-end">{{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2, ',', '.') : '-' }}</td>
                    <td>
                        <a href="{{ route('form-penilaian.show', $penilaian->id) }}" class="btn btn-sm btn-info" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('print.penilaian', $penilaian->id) }}" target="_blank" class="btn btn-sm btn-success" title="Print">
                            <i class="bi bi-printer"></i>
                        </a>
                        <a href="{{ route('form-penilaian.edit', $penilaian->id) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('form-penilaian.destroy', $penilaian->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                    onclick="return confirm('Hapus penilaian ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data penilaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $penilaians->links() }}
    </div>
</div>
@endsection