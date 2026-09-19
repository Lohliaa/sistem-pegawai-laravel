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
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

            .table-sm th,
            .table-sm td {
                padding: 0.2rem 0.3rem !important;
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
                <th width="200">Nama Pegawai</th>
                <td>{{ $penilaian->pegawai?->nama ?: '-' }}</td>
                <th width="200">Pejabat Penilai</th>
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
            <thead class="table-dark text-white">
                <tr>
                    <th class="text-center bg-dark text-white" style="width: 50px;">No</th>
                    <th class="bg-dark text-white">Uraian</th>
                    <th class="text-center bg-dark text-white" style="width: 80px;">Nilai</th>
                    <th class="bg-dark text-white" style="width: 250px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $detailMap = collect($detailItems)->keyBy('key'); 
                    $counter = 1;
                @endphp
                @foreach(\App\Models\PenilaianKinerja::getItems($penilaian->kategori) as $row)
                @if (($row['type'] ?? '') === 'section')
                <tr style="background-color: #b0b8c1;">
                    <td colspan="4" class="fw-bold">{{ $row['label'] }}</td>
                </tr>
                @elseif (($row['type'] ?? '') === 'sub')
                <tr class="table-light">
                    <td colspan="4" class="fw-bold ps-3">{{ $row['label'] }}</td>
                </tr>
                @else
                @php
                $isi = $detailMap->get($row['key']);
                $nilai = $isi['nilai'] ?? null;
                @endphp
                @if (!empty($row['sub']))
                <tr class="table-light">
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
                <tr class="table-light">
                    <th class="text-end" colspan="2">Nilai Total / Rata-rata</th>
                    <th class="text-center">{{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2, ',', '.') : '-' }}</th>
                    <th>Predikat: {{ $predikat['label'] ?? '-' }} ({{ $predikat['kode'] ?? '-' }})</th>
                </tr>
            </tbody>
        </table>
        @endif

        @if($penilaian->catatan)
        <div class="mb-4">
            <strong>Catatan:</strong>
            <p class="border p-2 rounded bg-light">{{ $penilaian->catatan }}</p>
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

        <div class="row mt-5">
            <div class="col-6 text-center">
                <p>Mengetahui,<br>Ketua Yayasan Permata Mojokerto,</p>
                <br><br><br>
                <p><strong>({{ $ketuaYayasan?->nama ?? '...................................' }})</strong></p>
            </div>
            <div class="col-6 text-center">
                <p>Kepala Bidang SDM,</p>
                <br><br><br>
                <p><strong>({{ $kabidSdm?->nama ?? '...................................' }})</strong></p>
            </div>
        </div>
    </div>
</body>

</html>