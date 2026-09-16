@extends('layouts.app')

@section('title', 'Periode Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-calendar3"></i> Periode Penilaian</h3>
        <small class="text-muted">Periode kuartal penilaian kinerja pegawai.</small>
    </div>
    <a href="{{ route('kinerja-periode.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Periode
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
                    <th>Kuartal</th>
                    <th>Periode Bulan</th>
                    <th width="100">Tahun</th>
                    <th width="120">Jml Penilaian</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodes as $index => $periode)
                <tr>
                    <td>{{ $periodes->firstItem() + $index }}</td>
                    <td><span class="badge bg-primary">{{ $periode->nama_kuartal }}</span></td>
                    <td>{{ $periode->periode_bulan }}</td>
                    <td>{{ $periode->tahun }}</td>
                    <td>{{ $periode->penilaian_count }}</td>
                    <td>
                        <a href="{{ route('kinerja-periode.show', $periode->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('kinerja-periode.edit', $periode->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kinerja-periode.destroy', $periode->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus periode {{ $periode->label }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada periode penilaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $periodes->links() }}
    </div>
</div>
@endsection