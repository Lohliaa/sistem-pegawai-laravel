@extends('layouts.app')

@section('title', 'Bahan Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-file-earmark-text"></i> Manajemen Bahan Penilaian</h3>
        <small class="text-muted">Bahan pendukung penilaian kinerja pegawai.</small>
    </div>
    <a href="{{ route('kinerja-bahan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Bahan
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Bahan</th>
                    <th>Link</th>
                    <th>Keterangan</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahans as $index => $bahan)
                <tr>
                    <td>{{ $bahans->firstItem() + $index }}</td>
                    <td>{{ $bahan->nama_bahan }}</td>
                    <td>
                        <a href="{{ $bahan->link }}" target="_blank" rel="noopener">{{ Str::limit($bahan->link, 50) }}</a>
                    </td>
                    <td>{{ $bahan->keterangan ?: '-' }}</td>
                    <td>
                        <a href="{{ route('kinerja-bahan.show', $bahan->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('kinerja-bahan.edit', $bahan->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kinerja-bahan.destroy', $bahan->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus bahan {{ $bahan->nama_bahan }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada bahan penilaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $bahans->links() }}
    </div>
</div>
@endsection