@extends('layouts.app')

@section('title', 'Pejabat Penilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-award"></i> Manajemen Pejabat Penilai</h3>
        <small class="text-muted">Pegawai yang bertanggung jawab menilai kinerja pegawai lain.</small>
    </div>
    <a href="{{ route('kinerja-pejabat.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Pejabat
    </a>
</div>
<hr>

@include('partials.errors')

@if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Unit</th>
                    <th width="110">Status</th>
                    <th>Keterangan</th>
                    <th width="120">Jml Penilaian</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pejabats as $index => $pejabat)
                <tr>
                    <td>{{ $pejabats->firstItem() + $index }}</td>
                    <td>{{ $pejabat->nama }}</td>
                    <td>{{ $pejabat->jabatan ?: '-' }}</td>
                    <td>{{ $pejabat->unit ?: '-' }}</td>
                    <td>
                        @if($pejabat->status_aktif === \App\Models\PejabatPenilai::STATUS_AKTIF)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $pejabat->keterangan ?: '-' }}</td>
                    <td>{{ $pejabat->penilaian_count }}</td>
                    <td>
                        <a href="{{ route('kinerja-pejabat.show', $pejabat->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('kinerja-pejabat.edit', $pejabat->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kinerja-pejabat.destroy', $pejabat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus pejabat {{ $pejabat->nama }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada pejabat penilai</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pejabats->links() }}
    </div>
</div>
@endsection