@extends('layouts.app')

@section('title', 'Detail Status Kepegawaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Status Kepegawaian</h3>
    <div>
        @if(auth()->user()->role !== 'staf')
           <a href="{{ route('kinerja-status.edit', $status->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        @endif
        <a href="{{ route('kinerja-status.index') }}" class="btn btn-secondary">
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
                <td>{{ $status->id }}</td>
            </tr>
            <tr>
                <th>Nama Status</th>
                <td>{{ $status->nama_status }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $status->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $status->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection