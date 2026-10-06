<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Penilaian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<style>
        body {
            background-color: #fff;
            color: #000;
            font-size: 14px;
        }

        /* 4. Border Seluruh Tabel 1px */
        table, .table-bordered, .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
        }

        /* 1. Header Tabel Rincian Penilaian (Background hitam, Teks putih) */
        .table-rincian-header, .table-rincian-header th {
            background-color: #000 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* 2. Kolom ASPEK utama (Kompetensi, Komitmen, Kinerja) -> Biru sedikit ke abu-abuan (muted blue/blue-gray) */
        tr.section-aspek {
            background-color: #4b6584 !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        tr.section-aspek td {
            color: #fff !important;
        }

        /* 3. Subaspek (Kedisiplinan, Keislaman, Pengembangan Diri) -> Abu-abu */
        .col-subaspek-bg {
            background-color: #d1d8e0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* 5. Bagian Catatan dengan Border 1px */
        .catatan-box {
            border: 1px solid #000 !important;
            padding: 10px !important;
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
                font-size: 12px;
            }

            .container {
                max-width: 100% !important;
            }
            
            table, .table-bordered, .table-bordered th, .table-bordered td {
                border: 1px solid #000 !important;
            }

            .table-rincian-header, .table-rincian-header th {
                background-color: #000 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            tr.section-aspek {
                background-color: #4b6584 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            tr.section-aspek td {
                color: #fff !important;
            }

            .col-subaspek-bg {
                background-color: #d1d8e0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .catatan-box {
                border: 1px solid #000 !important;
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="p-4">
    <div class="container">
        <div class="no-print mb-4 d-flex justify-content-between align-items-center">
            <div>
                <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>

        <!-- KOP SURAT -->
        <div class="d-flex align-items-center border-bottom pb-3 mb-4">
            <div class="flex-shrink-0 me-3">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 70px; width: auto;">
            </div>
            <div class="flex-grow-1 text-center">
                <h4 class="fw-bold text-uppercase mb-1">PENILAIAN KINERJA PENDIDIK DAN TENAGA KEPENDIDIKAN</h4>
                <h4 class="fw-bold text-uppercase mb-1">YAYASAN PERMATA MOJOKERTO</h4>
            </div>
            <div style="width: 70px;" class="d-none d-print-block"></div>
        </div>

        <table class="table table-bordered mb-4">
            <tr>
                <th width="150">Nama Pegawai</th>
                <td>{{ $penilaian->pegawai?->nama ?: '-' }}</td>
                <th width="150">Pejabat Penilai</th>
                <td>{{ $penilaian->pejabatPenilai?->nama ?: '-' }}</td>
            </tr>
            <tr>
                <th>Unit / Jabatan</th>
                <td>{{ $penilaian->pegawai?->unit ?: '-' }} / {{ $penilaian->pegawai?->jabatan ?: '-' }}</td>
                <th>Jabatan Penilai</th>
                <td>{{ $penilaian->pejabatPenilai?->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Status Kepegawaian</th>
                <td>{{ $penilaian->statusKepegawaian?->nama_status ?: '-' }}</td>
                <th>Periode</th>
                <td>{{ $penilaian->periode?->label ?? '-' }}</td>
            </tr>
        </table>

        <h5 class="mb-3">Rincian Penilaian</h5>
        @php $detailItems = $penilaian->detailItems(); @endphp
        @if (!empty($detailItems))
        <table class="table table-sm table-bordered align-middle mb-4">
            <thead class="table-rincian-header">
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Uraian</th>
                    <th class="text-center" style="width: 80px;">Nilai</th>
                    <th style="width: 250px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $detailMap = collect($detailItems)->keyBy('key'); 
                    $counter = 1;
                @endphp
                @foreach(\App\Models\PenilaianKinerja::getItems($penilaian->kategori) as $row)
                @if (($row['type'] ?? '') === 'section')
                <tr class="section-aspek">
                    <td colspan="4" class="fw-bold">{{ $row['label'] }}</td>
                </tr>
                @elseif (($row['type'] ?? '') === 'sub')
                <tr class="col-subaspek-bg">
                    <td colspan="4" class="fw-bold ps-3">{{ $row['label'] }}</td>
                </tr>
                @else
                @php
                $isi = $detailMap->get($row['key']);
                $nilai = $isi['nilai'] ?? null;
                @endphp
                @if (!empty($row['sub']))
                <tr class="col-subaspek-bg">
                    <td class="text-center fw-bold">{{ $counter++ }}</td>
                    <td><span class="fw-semibold">{{ $row['uraian'] }}</span></td>
                    <td class="text-center fw-semibold">{{ $nilai !== null ? str_replace('.', ',', rtrim(rtrim(number_format((float) $nilai, 2, '.', ''), '0'), '.')) : '-' }}</td>
                    <td>{{ $isi['catatan'] ?? '-' }}</td>
                </tr>
                @foreach($isi['sub'] ?? [] as $subPoin)
                <tr>
                    <td></td>
                    <td>
                        <div class="ps-3 small">{{ chr(97 + $subPoin['index']) }}. {{ $subPoin['uraian'] }}</div>
                    </td>
                    <td class="text-center">{{ $subPoin['nilai'] ?? '-' }}</td>
                    <td>{{ $subPoin['catatan'] ?? '-' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center fw-bold">{{ $counter++ }}</td>
                    <td><span class="fw-semibold">{{ $row['uraian'] }}</span></td>
                    <td class="text-center">{{ $nilai !== null ? str_replace('.', ',', rtrim(rtrim(number_format((float) $nilai, 2, '.', ''), '0'), '.')) : '-' }}</td>
                    <td>{{ $isi['catatan'] ?? '-' }}</td>
                </tr>
                @endif
                @endif
                @endforeach
                @php
                    $ringkasan = \App\Models\PenilaianKinerja::hitungRingkasanNilai($penilaian->detail_penilaian ?? [], $penilaian->kategori ?? 'pegawai');
                @endphp
                <tr class="table-light">
                    <th class="text-end" colspan="2">Total Kompetensi</th>
                    <th class="text-center">{{ number_format($ringkasan['total_kompetensi'], 2, ',', '.') }}</th>
                    <th></th>
                </tr>
                <tr class="table-light">
                    <th class="text-end" colspan="2">Total Komitmen</th>
                    <th class="text-center">{{ number_format($ringkasan['total_komitmen'], 2, ',', '.') }}</th>
                    <th></th>
                </tr>
                <tr class="table-light">
                    <th class="text-end" colspan="2">Total Kinerja</th>
                    <th class="text-center">{{ number_format($ringkasan['total_kinerja'], 2, ',', '.') }}</th>
                    <th></th>
                </tr>
                <tr class="table-light fw-bold">
                    <th class="text-end" colspan="2">Total Nilai Seluruh Aspek</th>
                    <th class="text-center">{{ number_format($ringkasan['total_seluruh_aspek'], 2, ',', '.') }}</th>
                    <th></th>
                </tr>
                <tr class="table-light fw-bold">
                    <th class="text-end" colspan="2">NILAI AKHIR</th>
                    <th class="text-center">{{ number_format($penilaian->nilai_total ?? $ringkasan['nilai_keseluruhan'], 2, ',', '.') }}</th>
                    <th></th>
                </tr>
            </tbody>
        </table>
        @endif

        @if($penilaian->catatan)
        <div class="mb-4">
            <strong>Catatan:</strong>
            <div class="catatan-box mt-1">
                {!! nl2br(e($penilaian->catatan)) !!}
            </div>
        </div>
        @endif

        <div class="row mt-5">
            <div class="col-6 text-center">
                <p>Kabid/Kanit,</p>
                <br><br><br>
                <p><strong>({{ $penilaian->pejabatPenilai?->nama ?? '...................................' }})</strong></p>
            </div>
            <div class="col-6 text-center">
                <p>Mojokerto, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Pendidik/Tenaga Kependidikan yang dinilai,</p>
                <br><br><br>
                <p><strong>({{ $penilaian->pegawai?->nama ?? '...................................' }})</strong></p>
            </div>
        </div>

        @php
            $namaKetuaYayasan = $ketuaYayasan?->nama ?? \App\Models\Pegawai::where('jabatan', 'Ketua Yayasan Permata Mojokerto')->value('nama');
            $namaKabidSdm = $kabidSdm?->nama ?? \App\Models\Pegawai::where('jabatan', 'Kepala Bidang SDM')->value('nama');
        @endphp

        <div class="row mt-5">
            <div class="col-6 text-center">
                <p class="mb-1">Mengetahui,<br>Ketua Yayasan Permata Mojokerto,</p>
                <div class="d-flex justify-content-center align-items-center" style="height: 80px;">
                    <img src="{{ asset('img/ttd.png') }}" alt="Tanda Tangan Ketua Yayasan" style="max-height: 140px; max-width: 180px; object-fit: contain;">
                </div>
                <p class="mt-1"><strong>({{ $namaKetuaYayasan ?? '...................................' }})</strong></p>
            </div>
            <div class="col-6 text-center">
                <p class="mb-1">Kepala Bidang SDM,</p>
                <div style="height: 80px;"></div>
                <p class="mt-1"><strong>({{ $namaKabidSdm ?? '...................................' }})</strong></p>
            </div>
        </div>
    </div>
</body>

</html>