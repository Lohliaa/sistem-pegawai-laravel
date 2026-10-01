@extends('layouts.app')

@section('title', 'SOP Kepegawaian')

@section('content')

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('sop-kepegawaian.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                       placeholder="Judul SOP">
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" title="Cari"><i class="bi bi-search"></i></button>
                    @if(auth()->user()->role == 'admin')
                    <a href="{{ route('sop-kepegawaian.create') }}" class="btn btn-primary" title="Tambah SOP">
                        <i class="bi bi-plus-circle"></i> Tambah SOP
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="60">No</th>
                    <th>Judul SOP</th>
                    <th>Dokumen</th>
                    <th>Terakhir Diperbarui</th>
                    @if(auth()->user()->role == 'admin')
                    <th width="200">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($sops as $index => $sop)
                <tr>
                    <td>{{ $sops->firstItem() + $index }}</td>
                    <td>{{ $sop->judul_sop }}</td>
                    <td>
                        @if($sop->dokumen)
                        <a href="{{ route('sop-kepegawaian.download', $sop->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Download PDF">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($sop->terakhir_diperbarui)
                            {{ $sop->terakhir_diperbarui->format('d-m-Y') }}
                        @else
                            -
                        @endif
                    </td>
                    @if(auth()->user()->role == 'admin')
                    <td>
                        <a href="{{ route('sop-kepegawaian.show', $sop->id) }}" class="btn btn-sm btn-info" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('sop-kepegawaian.edit', $sop->id) }}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('sop-kepegawaian.destroy', $sop->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus SOP Kepegawaian {{ $sop->judul_sop }}?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role == 'admin' ? '5' : '4' }}" class="text-center">Belum ada SOP Kepegawaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $sops->links() }}
    </div>
</div>

@endsection
