@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Penilaian Kinerja</h3>
    <a href="{{ route('form-penilaian.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('form-penilaian.update', $penilaian->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="pegawai_id" class="form-label">Pegawai <span class="text-danger">*</span></label>
                    <select name="pegawai_id" id="pegawai_id" class="form-select" required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" @selected(old('pegawai_id', $penilaian->pegawai_id) === $pegawai->id)>{{ $pegawai->nama }}{{ $pegawai->jabatan ? ' - '.$pegawai->jabatan : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="periode_id" class="form-label">Periode <span class="text-danger">*</span></label>
                    <select name="periode_id" id="periode_id" class="form-select" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}" @selected(old('periode_id', $penilaian->periode_id) === $periode->id)>{{ $periode->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="pejabat_penilai_id" class="form-label">Pejabat Penilai <span class="text-danger">*</span></label>
                    <select name="pejabat_penilai_id" id="pejabat_penilai_id" class="form-select" required>
                        <option value="">-- Pilih Pejabat --</option>
                        @foreach($pejabats as $pejabat)
                            <option value="{{ $pejabat->id }}" @selected(old('pejabat_penilai_id', $penilaian->pejabat_penilai_id) == $pejabat->id)>{{ $pejabat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="status_kepegawaian_id" class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                    <select name="status_kepegawaian_id" id="status_kepegawaian_id" class="form-select" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach($statusKepegawaians as $status)
                            <option value="{{ $status->id }}" @selected(old('status_kepegawaian_id', $penilaian->status_kepegawaian_id) == $status->id)>{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>
            <h5 class="mb-3"><i class="bi bi-list-check"></i> Rincian Penilaian (0-4)</h5>

            @php
                $storedDetail = $penilaian->detail_penilaian ?? [];
                $currentDetail = [];
                $kategori = $kategori ?? $penilaian->kategori ?? 'pegawai';
                foreach (\App\Models\PenilaianKinerja::itemKeys($kategori) as $itemKey) {
                    $storedRow = $storedDetail[$itemKey] ?? [];

                    $currentDetail[$itemKey] = [
                        'nilai' => old('nilai.'.$itemKey, $storedRow['nilai'] ?? ''),
                        'catatan' => old('catatan_baris.'.$itemKey, $storedRow['catatan'] ?? ''),
                    ];

                    $definisi = \App\Models\PenilaianKinerja::itemByKey($itemKey, $kategori);
                    foreach ($definisi['sub'] ?? [] as $i => $subDef) {
                        $storedSub = $storedRow['sub'][$i] ?? [];
                        $currentDetail[$itemKey]['sub'][$i] = [
                            'nilai' => old('nilai.'.$itemKey.'.sub.'.$i, $storedSub['nilai'] ?? ''),
                            'catatan' => old('catatan_poin.'.$itemKey.'.'.$i, $storedSub['catatan'] ?? ''),
                        ];
                    }
                }
            @endphp
            @include('partials.form-penilaian-table', ['items' => $items, 'current' => $currentDetail])

            <div class="mb-3 mt-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea name="catatan" id="catatan" rows="3" class="form-control"
                          placeholder="Catatan tambahan (opsional)">{{ old('catatan', $penilaian->catatan) }}</textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('form-penilaian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection