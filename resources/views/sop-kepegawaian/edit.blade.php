@extends('layouts.app')

@section('title', 'Edit SOP Kepegawaian')

@section('content')

@include('partials.errors')

<div class="card">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit SOP Kepegawaian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sop-kepegawaian.update', $sop->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul_sop" class="form-label">Judul SOP <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('judul_sop') is-invalid @enderror" 
                       id="judul_sop" name="judul_sop" value="{{ old('judul_sop', $sop->judul_sop) }}" placeholder="Masukkan judul SOP" required>
                @error('judul_sop')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="dokumen" class="form-label">Dokumen PDF</label>
                @if($sop->dokumen)
                    <div class="mb-2">
                        <p class="mb-1"><small class="text-muted">Dokumen saat ini:</small></p>
                        <a href="{{ route('sop-kepegawaian.download', $sop->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="bi bi-file-pdf"></i> Lihat PDF
                        </a>
                    </div>
                @endif
                <input type="file" class="form-control @error('dokumen') is-invalid @enderror" 
                       id="dokumen" name="dokumen" accept=".pdf">
                <small class="form-text text-muted">Format: PDF | Ukuran maksimal: 10 MB | Kosongkan jika tidak ingin mengubah dokumen</small>
                @error('dokumen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>
                <a href="{{ route('sop-kepegawaian.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
