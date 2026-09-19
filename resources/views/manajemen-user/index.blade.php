@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('manajemen-user.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Username / Nama Pegawai">
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach(\App\Models\User::ROLES as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(request('role') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" title="Terapkan"><i class="bi bi-search"></i></button>
                    <a href="{{ route('manajemen-user.export') }}" class="btn btn-success text-white" title="Export Excel">
                        <i class="bi bi-download"></i>
                    </a>
                    <label class="btn btn-outline-primary mb-0" data-bs-toggle="modal" data-bs-target="#userImportModal" title="Upload Excel User" style="cursor: pointer;">
                        <i class="bi bi-upload"></i>
                    </label>
                    <a href="{{ route('manajemen-user.create') }}" class="btn btn-primary" title="Tambah User">
                        <i class="bi bi-plus-circle"></i>
                    </a>
                </div>
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
                    <th>Username</th>
                    <th>Role</th>
                    <th>Nama Pegawai</th>
                    <th>Unit</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</td>
                    <td>{{ $user->pegawai?->nama ?? '-' }}</td>
                    <td>{{ $user->pegawai?->unit ?? '-' }}</td>
                    <td>
                        <a href="{{ route('manajemen-user.show', $user->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('manajemen-user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('manajemen-user.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus user {{ $user->username }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>

<!-- Modal Upload Excel User -->
<div class="modal fade" id="userImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('manajemen-user.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload"></i> Upload Excel User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="file" name="file_excel" class="form-control" accept=".xls,.xlsx,.csv,.txt" required>
                <small class="text-muted d-block mt-2">Format: .xls, .xlsx, .csv, .txt (maks 10 MB)</small>

                <hr>

                <h6 class="mb-2">Judul kolom (baris pertama = judul, akan dilewati):</h6>
                <ol class="small text-muted mb-2 ps-3">
                    <li>Username (wajib, unik)</li>
                    <li>Password (opsional, default: 123456)</li>
                    <li>Role (admin / staf / kanit / kabid, default: staf)</li>
                    <li>Nama Pegawai</li>
                    <li>Tempat Lahir</li>
                    <li>Tanggal Lahir</li>
                    <li>Jenis Kelamin (L / P)</li>
                    <li>Alamat</li>
                    <li>Unit</li>
                    <li>Jabatan</li>
                    <li>Tanggal TMT</li>
                </ol>
                <small class="text-info d-block mb-2">
                    <i class="bi bi-info-circle"></i>
                    Kolom jika berdasarkan judul (header) &mdash; urutan kolom bebas.
                    Kolom "Unit" dat selalu na kolom Unit di sistem,
                    kolom "Alamat" dat na kolom Alamat.
                </small>
                <small class="text-muted">
                    Setiap baris akan membuat akun user sekaligus menghubungkannya dengan
                    profil pegawai (<i>profile-pegawai</i>). Username yang sudah terdaftar dilewati.
                </small>
                <div class="mt-3">
                    <a href="{{ route('manajemen-user.template') }}" class="btn btn-sm btn-outline-secondary">
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