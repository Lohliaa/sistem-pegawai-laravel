@extends('layouts.app')

@section('title', 'Data SK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-file-earmark-text"></i> Data SK</h3>
        <small class="text-muted">Daftar data SK (Surat Keputusan) pegawai.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('data-sk.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Excel
        </a>
        <label class="btn btn-outline-primary mb-0" data-bs-toggle="modal" data-bs-target="#skImportModal">
            <i class="bi bi-upload"></i> Import Excel
        </label>
        <a href="{{ route('data-sk.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>
</div>
<hr>

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('data-sk.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
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
                <a href="{{ route('data-sk.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                    <th>Jabatan</th>
                    <th class="text-end">Gaji Pokok</th>
                    <th class="text-end">THP</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sks as $index => $sk)
                <tr>
                    <td>{{ $sks->firstItem() + $index }}</td>
                    <td>{{ $sk->no_sk ?: '-' }}</td>
                    <td>{{ $sk->nama }}</td>
                    <td>{{ $sk->status_kepegawaian ?: '-' }}</td>
                    <td>{{ $sk->unit_kerja ?: '-' }}</td>
                    <td>{{ $sk->jabatan ?: '-' }}</td>
                    <td class="text-end">{{ $sk->gaji_pokok ? number_format((float) $sk->gaji_pokok, 0, ',', '.') : '-' }}</td>
                    <td class="text-end">{{ $sk->thp ? number_format((float) $sk->thp, 0, ',', '.') : '-' }}</td>
                    <td>
                        <a href="{{ route('data-sk.show', $sk->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('data-sk.edit', $sk->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('data-sk.destroy', $sk->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus data SK {{ $sk->nama }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada data SK</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $sks->links() }}
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="skImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('data-sk.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload"></i> Import Data SK dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx,.csv,.txt" required>
                <small class="text-muted">Format: .xls, .xlsx, .csv, .txt (maks 5 MB)</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>
@endsection