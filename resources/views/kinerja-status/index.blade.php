@extends('layouts.app')

@section('title', 'Status Kepegawaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0"><i class="bi bi-person-check"></i> Status Kepegawaian</h3>
        <small class="text-muted">Daftar status kepegawaian (contoh: PT, PKWTT, PKWT).</small>
    </div>
    @if(auth()->user()->role !== 'staf')
    <a href="{{ route('kinerja-status.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Status
    </a>
    @endif
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Status</th>
                    <th width="180">Dibuat</th>
                    <th width="{{ auth()->user()->role !== 'staf' ? '200' : '80' }}">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statuses as $index => $status)
                <tr>
                    <td>{{ $statuses->firstItem() + $index }}</td>
                    <td>{{ $status->nama_status }}</td>
                    <td>{{ $status->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('kinerja-status.show', $status->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(auth()->user()->role !== 'staf')
                        <a href="{{ route('kinerja-status.edit', $status->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kinerja-status.destroy', $status->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus status {{ $status->nama_status }}?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data status kepegawaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $statuses->links() }}
    </div>
</div>
@endsection