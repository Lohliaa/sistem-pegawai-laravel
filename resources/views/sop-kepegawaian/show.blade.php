@extends('layouts.app')

@section('title', 'Detail SOP Kepegawaian')

@section('content')

<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-eye"></i> Detail SOP Kepegawaian</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Judul SOP</label>
            <p class="form-control-plaintext">{{ $sop->judul_sop }}</p>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Dokumen</label>
            <p>
                @if($sop->dokumen)
                    <a href="{{ route('sop-kepegawaian.download', $sop->id) }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="bi bi-file-pdf"></i> Buka PDF
                    </a>
                @else
                    <span class="text-muted">-</span>
                @endif
            </p>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Terakhir Diperbarui</label>
            <p class="form-control-plaintext">
                @if($sop->terakhir_diperbarui)
                    {{ $sop->terakhir_diperbarui->format('d-m-Y') }}
                @else
                    -
                @endif
            </p>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Dibuat</label>
            <p class="form-control-plaintext">{{ $sop->created_at->format('d-m-Y H:i') }}</p>
        </div>

        <div class="d-flex gap-2">
            @if(auth()->user()->role == 'admin')
            <a href="{{ route('sop-kepegawaian.edit', $sop->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @endif
            <a href="{{ route('sop-kepegawaian.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

@endsection
