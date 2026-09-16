@extends('layouts.app')

@section('title', 'Edit Status Kepegawaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Status Kepegawaian</h3>
    <a href="{{ route('kinerja-status.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('kinerja-status.update', $status->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama_status" class="form-label">Nama Status <span class="text-danger">*</span></label>
                <input type="text" name="nama_status" id="nama_status" class="form-control @error('nama_status') is-invalid @enderror"
                       value="{{ old('nama_status', $status->nama_status) }}" required>
                @error('nama_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection