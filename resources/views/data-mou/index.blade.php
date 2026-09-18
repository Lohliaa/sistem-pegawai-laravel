@extends('layouts.app')

@section('title', 'Data MOU')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-file-earmark-ruled"></i> Data MOU</h3>
        <small class="text-muted">Daftar data MOU (Master Outsource / Upah) pegawai.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('data-mou.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Excel
        </a>
        <label class="btn btn-outline-primary mb-0" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload"></i> Import Excel
        </label>
        <a href="{{ route('data-mou.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>
</div>
<hr>

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('data-mou.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Nama / No. SK / Unit Kerja">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status Kepegawaian</label>
                <select name="status_kepegawaian" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($statusList as $status)
                        <option value="{{ $status->nama_status }}" @selected(($filters['status_kepegawaian'] ?? '') === $status->nama_status)>{{ $status->nama_status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Terapkan</button>
                <a href="{{ route('data-mou.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th>No. SK</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Unit Kerja</th>
                    <th class="text-end">Gaji Pokok</th>
                    <th class="text-end">THP</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mous as $index => $mou)
                <tr>
                    <td>{{ $mous->firstItem() + $index }}</td>
                    <td>{{ $mou->no_sk ?: '-' }}</td>
                    <td>{{ $mou->nama }}</td>
                    <td>{{ $mou->status_kepegawaian ?: '-' }}{{ $mou->status_detail ? '/'.$mou->status_detail : '' }}</td>
                    <td>{{ $mou->unit_kerja ?: '-' }}</td>
                    <td class="text-end">{{ $mou->gaji_pokok ? number_format((float) $mou->gaji_pokok, 0, ',', '.') : '-' }}</td>
                    <td class="text-end">{{ $mou->thp ? number_format((float) $mou->thp, 0, ',', '.') : '-' }}</td>
                    <td>
                        <a href="{{ route('data-mou.show', $mou->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('data-mou.edit', $mou->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('data-mou.destroy', $mou->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus data MOU {{ $mou->nama }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data MOU</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $mous->links() }}
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('data-mou.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload"></i> Import Data MOU dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx,.csv,.txt" required>
                <small class="text-muted">Format: .xls, .xlsx, .csv, .txt (maks 10 MB)</small>
                <div class="mt-3">
                    <a href="{{ route('data-mou.template') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-file-earmark-arrow-down"></i> Download Template
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>
@endsection