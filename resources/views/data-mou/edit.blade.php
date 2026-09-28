@extends('layouts.app')

@section('title', 'Edit Data MOU')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-pencil"></i> Edit Data MOU</h3>
    <a href="{{ route('data-mou.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
<hr>

@include('partials.errors')

<div class="card">
    <div class="card-body">
        <form action="{{ route('data-mou.update', $mou->id) }}" method="POST" id="formMou">
            @csrf
            @method('PUT')
            
            <h5 class="mb-3 text-primary"><i class="bi bi-person-badge"></i> Data Pokok & Personal</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="no_sk" class="form-label">Nomer Surat <span class="text-danger">*</span></label>
                    <input type="text" name="no_sk" id="no_sk" class="form-control" value="{{ old('no_sk', $mou->no_sk) }}" required>
                </div>
                <div class="col-md-3">
                    <label for="no_tambahan" class="form-label">No. Tambahan</label>
                    <input type="text" name="no_tambahan" id="no_tambahan" class="form-control" value="{{ old('no_tambahan', $mou->no_tambahan) }}">
                </div>
                <div class="col-md-3">
                    <label for="status_kepegawaian" class="form-label">Status Kepegawaian</label>
                    <select name="status_kepegawaian" id="status_kepegawaian" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach($statusList as $status)
                            <option value="{{ $status->nama_status }}" @selected(old('status_kepegawaian', $mou->status_kepegawaian) === $status->nama_status)>{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status_detail" class="form-label">Status Detail</label>
                    <input type="text" name="status_detail" id="status_detail" class="form-control" value="{{ old('status_detail', $mou->status_detail) }}" placeholder="Guru Al Quran SDIT">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $mou->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="gelar" class="form-label">Gelar</label>
                    <input type="text" name="gelar" id="gelar" class="form-control" value="{{ old('gelar', $mou->gelar) }}" placeholder="S.Pd., M.Kom.">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $mou->tempat_lahir) }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $mou->tanggal_lahir) }}">
                </div>
                <div class="col-md-3">
                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                    <input type="text" name="unit_kerja" id="unit_kerja" class="form-control" value="{{ old('unit_kerja', $mou->unit_kerja) }}" placeholder="Contoh: Yayasan">
                </div>
                <div class="col-md-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat', $mou->alamat) }}">
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-3">
                    <label for="hari_kerja" class="form-label">Hari Kerja (Angka)</label>
                    <input type="number" name="hari_kerja" id="hari_kerja" class="form-control" value="{{ old('hari_kerja', $mou->hari_kerja) }}" placeholder="5">
                </div>
                <div class="col-md-3">
                    <label for="jam_kerja" class="form-label">Jam Kerja</label>
                    <input type="text" name="jam_kerja" id="jam_kerja" class="form-control" value="{{ old('jam_kerja', $mou->jam_kerja) }}" placeholder="Senin-Jumat 06.50-14.30">
                </div>
                <div class="col-md-3">
                    <label for="hari" class="form-label">Hari MOU</label>
                    <input type="text" name="hari" id="hari" class="form-control" value="{{ old('hari', $mou->hari) }}" placeholder="Senin">
                </div>
                <div class="col-md-3">
                    <label for="tgl_mou" class="form-label">Tanggal MOU</label>
                    <input type="date" name="tgl_mou" id="tgl_mou" class="form-control" value="{{ old('tgl_mou', $mou->tgl_mou) }}">
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3 text-primary"><i class="bi bi-cash-stack"></i> Informasi Finansial & Gaji</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                    <input type="text" name="gaji_pokok" id="gaji_pokok" class="form-control financial" value="{{ old('gaji_pokok', $mou->gaji_pokok) }}" placeholder="0">
                </div>
                <div class="col-md-4">
                    <label for="tunjangan_jabatan" class="form-label">Tunjangan Jabatan</label>
                    <input type="text" name="tunjangan_jabatan" id="tunjangan_jabatan" class="form-control financial" value="{{ old('tunjangan_jabatan', $mou->tunjangan_jabatan) }}" placeholder="0">
                </div>
                <div class="col-md-4">
                    <label for="tunjangan_transport" class="form-label">Tunjangan Transport</label>
                    <input type="text" name="tunjangan_transport" id="tunjangan_transport" class="form-control financial" value="{{ old('tunjangan_transport', $mou->tunjangan_transport) }}" placeholder="0">
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-3">
                    <label for="tunjangan_kinerja" class="form-label">Tunjangan Kinerja</label>
                    <input type="text" name="tunjangan_kinerja" id="tunjangan_kinerja" class="form-control financial" value="{{ old('tunjangan_kinerja', $mou->tunjangan_kinerja) }}" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="tunjangan_fungsional" class="form-label">Tunjangan Fungsional</label>
                    <input type="text" name="tunjangan_fungsional" id="tunjangan_fungsional" class="form-control financial" value="{{ old('tunjangan_fungsional', $mou->tunjangan_fungsional) }}" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="penyetaraan" class="form-label">Penyetaraan</label>
                    <input type="text" name="penyetaraan" id="penyetaraan" class="form-control financial" value="{{ old('penyetaraan', $mou->penyetaraan) }}" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label for="thp" class="form-label fw-bold text-success">THP (Total Take Home Pay)</label>
                    <input type="text" name="thp" id="thp" class="form-control bg-light fw-bold text-success" value="{{ old('thp', $mou->thp) }}" readonly placeholder="0">
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-12">
                    <label for="terbilang" class="form-label fw-bold">Terbilang</label>
                    <input type="text" name="terbilang" id="terbilang" class="form-control bg-light" value="{{ old('terbilang', $mou->terbilang) }}" readonly placeholder="Otomatis terbilang dari THP">
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3 text-primary"><i class="bi bi-calendar-event"></i> Masa Berlaku & Saksi</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" value="{{ old('tgl_mulai', $mou->tgl_mulai) }}">
                </div>
                <div class="col-md-4">
                    <label for="berlaku" class="form-label">Berlaku</label>
                    <input type="text" name="berlaku" id="berlaku" class="form-control" value="{{ old('berlaku', $mou->berlaku) }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir', $mou->tanggal_akhir) }}">
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label for="saksi1" class="form-label">Saksi 1</label>
                    <input type="text" name="saksi1" id="saksi1" class="form-control" value="{{ old('saksi1', $mou->saksi1) }}">
                </div>
                <div class="col-md-6">
                    <label for="saksi2" class="form-label">Saksi 2</label>
                    <input type="text" name="saksi2" id="saksi2" class="form-control" value="{{ old('saksi2', $mou->saksi2) }}">
                </div>
            </div>

            <div class="mt-4 pt-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('data-mou.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const financialFields = document.querySelectorAll('.financial');
    const thpInput = document.getElementById('thp');
    const terbilangInput = document.getElementById('terbilang');

    function cleanNumber(str) {
        if (!str) return 0;
        let cleaned = str.toString().replace(/[^0-9]/g, '');
        return parseInt(cleaned, 10) || 0;
    }

    function formatRupiahNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function penyebut(nilai) {
        nilai = Math.abs(nilai);
        var baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        var hasil = "";
        if (nilai < 12) {
            hasil = " " + baca[nilai];
        } else if (nilai < 20) {
            hasil = penyebut(nilai - 10) + " Belas";
        } else if (nilai < 100) {
            hasil = penyebut(Math.floor(nilai / 10)) + " Puluh" + penyebut(nilai % 10);
        } else if (nilai < 200) {
            hasil = " Seratus" + penyebut(nilai - 100);
        } else if (nilai < 1000) {
            hasil = penyebut(Math.floor(nilai / 100)) + " Ratus" + penyebut(nilai % 100);
        } else if (nilai < 2000) {
            hasil = " Seribu" + penyebut(nilai - 1000);
        } else if (nilai < 1000000) {
            hasil = penyebut(Math.floor(nilai / 1000)) + " Ribu" + penyebut(nilai % 1000);
        } else if (nilai < 1000000000) {
            hasil = penyebut(Math.floor(nilai / 1000000)) + " Juta" + penyebut(nilai % 1000000);
        } else if (nilai < 1000000000000) {
            hasil = penyebut(Math.floor(nilai / 1000000000)) + " Miliar" + penyebut(nilai % 1000000000);
        } else if (nilai < 1000000000000000) {
            hasil = penyebut(Math.floor(nilai / 1000000000000)) + " Triliun" + penyebut(nilai % 1000000000000);
        }
        return hasil;
    }

    function terbilang(nilai) {
        if (isNaN(nilai) || nilai === 0) {
            return 'Nol Rupiah';
        }
        var hasil = penyebut(nilai).trim();
        return hasil + " Rupiah";
    }

    function calculateTHP() {
        let total = 0;
        financialFields.forEach(field => {
            total += cleanNumber(field.value);
        });

        thpInput.value = formatRupiahNumber(total);
        terbilangInput.value = terbilang(total);
    }

    financialFields.forEach(field => {
        field.addEventListener('input', calculateTHP);
    });

    calculateTHP();
});
</script>
@endsection