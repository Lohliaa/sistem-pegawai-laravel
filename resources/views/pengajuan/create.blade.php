@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')

@section('content')
<h2>Buat Pengajuan Baru</h2>
<hr>

<div class="card">
    <div class="card-body">
        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Nama -->
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama *</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', auth()->user()->pegawai?->nama ?? auth()->user()->username) }}" readonly>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div class="col-md-6 mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir *</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Unit -->
                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit *</label>
                    <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror" required>
                        <option value="">-- Pilih Unit --</option>
                        <option value="Daycare" {{ old('unit') == 'Daycare' ? 'selected' : '' }}>Daycare</option>
                        <option value="TPA" {{ old('unit') == 'TPA' ? 'selected' : '' }}>TPA</option>
                        <option value="Preschool" {{ old('unit') == 'Preschool' ? 'selected' : '' }}>Preschool</option>
                        <option value="KBIT" {{ old('unit') == 'KBIT' ? 'selected' : '' }}>KBIT</option>
                        <option value="TKIT" {{ old('unit') == 'TKIT' ? 'selected' : '' }}>TKIT</option>
                        <option value="TK Islam Platinum" {{ old('unit') == 'TK Islam Platinum' ? 'selected' : '' }}>TK Islam Platinum</option>
                        <option value="MI" {{ old('unit') == 'MI' ? 'selected' : '' }}>MI</option>
                        <option value="SDIT" {{ old('unit') == 'SDIT' ? 'selected' : '' }}>SDIT</option>
                        <option value="SMPIT" {{ old('unit') == 'SMPIT' ? 'selected' : '' }}>SMPIT</option>
                        <option value="MA" {{ old('unit') == 'MA' ? 'selected' : '' }}>MA</option>
                        <option value="PKBM" {{ old('unit') == 'PKBM' ? 'selected' : '' }}>PKBM</option>
                        <option value="Yayasan" {{ old('unit') == 'Yayasan' ? 'selected' : '' }}>Yayasan</option>
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pimpinan Atasan Langsung -->
                <div class="col-md-6 mb-3">
                    <label for="pimpinan_atasan" class="form-label">Pimpinan Atasan Langsung *</label>
                    <select name="pimpinan_atasan" id="pimpinan_atasan" class="form-select @error('pimpinan_atasan') is-invalid @enderror" required>
                        <option value="">-- Pilih Atasan Langsung --</option>
                        @foreach($pejabatList as $pejabat)
                            <option value="{{ $pejabat->nama }}" {{ old('pimpinan_atasan') == $pejabat->nama ? 'selected' : '' }}>
                                {{ $pejabat->nama }} {{ $pejabat->jabatan ? '('.$pejabat->jabatan.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('pimpinan_atasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal TMT -->
                <div class="col-md-6 mb-3">
                    <label for="tanggal_tmt" class="form-label">Tanggal TMT *</label>
                    <input type="date" name="tanggal_tmt" id="tanggal_tmt" class="form-control @error('tanggal_tmt') is-invalid @enderror" value="{{ old('tanggal_tmt') }}" required>
                    @error('tanggal_tmt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipe Pengajuan -->
                <div class="col-md-6 mb-3">
                    <label for="tipe_pengajuan" class="form-label">Tipe Pengajuan *</label>
                    <select name="tipe_pengajuan" id="tipe_pengajuan" class="form-select @error('tipe_pengajuan') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="MOU" {{ old('tipe_pengajuan') == 'MOU' ? 'selected' : '' }}>MOU</option>
                        <option value="SK" {{ old('tipe_pengajuan') == 'SK' ? 'selected' : '' }}>SK</option>
                        <option value="Lainnya" {{ old('tipe_pengajuan') == 'Lainnya' || (old('tipe_pengajuan') && !in_array(old('tipe_pengajuan'), ['MOU', 'SK'])) ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('tipe_pengajuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tipe Pengajuan Lainnya (Conditional) -->
                <div class="col-md-6 mb-3" id="wrapper_tipe_pengajuan_lainnya" style="display: none;">
                    <label for="tipe_pengajuan_lainnya" class="form-label">Tipe Pengajuan Lainnya *</label>
                    <input type="text" name="tipe_pengajuan_lainnya" id="tipe_pengajuan_lainnya" class="form-control @error('tipe_pengajuan_lainnya') is-invalid @enderror" value="{{ old('tipe_pengajuan_lainnya', (!in_array(old('tipe_pengajuan'), ['MOU', 'SK', '']) ? old('tipe_pengajuan') : '')) }}" placeholder="Masukkan tipe pengajuan">
                    @error('tipe_pengajuan_lainnya')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload File -->
                <div class="col-md-6 mb-3">
                    <label for="file_pengajuan" class="form-label">Upload File (PDF, Word, JPG, PNG - Opsional)</label>
                    <input type="file" name="file_pengajuan" id="file_pengajuan" class="form-control @error('file_pengajuan') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div class="form-text">Format yang diperbolehkan: PDF, DOC, DOCX, JPG, JPEG, PNG. Maksimal 10MB.</div>
                    @error('file_pengajuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="col-md-12 mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Pengajuan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectTipe = document.getElementById('tipe_pengajuan');
    const wrapperLainnya = document.getElementById('wrapper_tipe_pengajuan_lainnya');
    const inputLainnya = document.getElementById('tipe_pengajuan_lainnya');

    function toggleLainnya() {
        if (selectTipe.value === 'Lainnya') {
            wrapperLainnya.style.display = 'block';
            inputLainnya.setAttribute('required', 'required');
        } else {
            wrapperLainnya.style.display = 'none';
            inputLainnya.removeAttribute('required');
            inputLainnya.value = '';
        }
    }

    selectTipe.addEventListener('change', toggleLainnya);
    toggleLainnya();
});
</script>
@endpush
@endsection
