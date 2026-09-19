@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Penilaian Kinerja</h3>
    <div>
        <a href="{{ route('form-penilaian.edit', $penilaian->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('print.penilaian', $penilaian->id) }}" target="_blank" class="btn btn-success">
            <i class="bi bi-printer"></i> Print
        </a>
        <a href="{{ route('form-penilaian.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <strong>{{ $penilaian->pegawai?->nama ?? '-' }}</strong>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">Pegawai</th>
                <td>{{ $penilaian->pegawai?->nama ?: '-' }}</td>
            </tr>
            <tr>
                <th>Unit / Jabatan</th>
                <td>{{ $penilaian->pegawai?->unit ?: '-' }} / {{ $penilaian->pegawai?->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Periode</th>
                <td>{{ $penilaian->periode?->label ?? '-' }}</td>
            </tr>
            <tr>
                <th>Pejabat Penilai</th>
                <td>{{ $penilaian->pejabatPenilai?->nama ?: '-' }} ({{ $penilaian->pejabatPenilai?->jabatan ?: '-' }})</td>
            </tr>
            <tr>
                <th>Status Kepegawaian</th>
                <td>{{ $penilaian->statusKepegawaian?->nama_status ?: '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $penilaian->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $penilaian->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <strong><i class="bi bi-list-check"></i> Rincian Penilaian</strong>
    </div>
    <div class="card-body">
        @php $detailItems = $penilaian->detailItems(); @endphp

        @if (!empty($detailItems))
        <table class="table table-sm table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Uraian</th>
                    <th class="text-center" style="width: 80px;">Nilai</th>
                    <th style="width: 240px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $detailMap = collect($detailItems)->keyBy('key');
                    $counter = 1;
                @endphp
                @foreach(\App\Models\PenilaianKinerja::getItems($penilaian->kategori) as $row)
                    @if (($row['type'] ?? '') === 'section')
                        <tr class="table-primary">
                            <td colspan="4" class="fw-bold">{{ $row['label'] }}</td>
                        </tr>
                    @elseif (($row['type'] ?? '') === 'sub')
                        <tr class="table-secondary">
                            <td colspan="4" class="fw-bold ps-4">{{ $row['label'] }}</td>
                        </tr>
                    @else
                        @php
                            $isi = $detailMap->get($row['key']);
                            $nilai = $isi['nilai'] ?? null;
                        @endphp
                        @if (!empty($row['sub']))
                            <tr class="table-warning">
                                <td class="text-center fw-bold">{{ $counter++ }}</td>
                                <td><span class="fw-semibold">{{ $row['uraian'] }}</span></td>
                                <td class="text-center fw-semibold">{{ $nilai !== null ? str_replace('.', ',', rtrim(rtrim(number_format((float) $nilai, 2, '.', ''), '0'), '.')) : '-' }}</td>
                                <td>{{ $isi['catatan'] ?? '-' }}</td>
                            </tr>
                            @foreach($isi['sub'] ?? [] as $subPoin)
                                <tr>
                                    <td></td>
                                    <td><div class="ps-4 small">{{ chr(97 + $subPoin['index']) }}. {{ $subPoin['uraian'] }}</div></td>
                                    <td class="text-center">{{ $subPoin['nilai'] ?? '-' }}</td>
                                    <td>{{ $subPoin['catatan'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center fw-bold">{{ $counter++ }}</td>
                                <td><span class="fw-semibold">{{ $row['uraian'] }}</span></td>
                                <td class="text-center">{{ $nilai ?? '-' }}</td>
                                <td>{{ $isi['catatan'] ?? '-' }}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach
                <tr class="table-active">
                    <th class="text-end" colspan="4">Total (Jumlah &amp; Rata-rata): {{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2, ',', '.') : '-' }}</th>
                </tr>
            </tbody>
        </table>
        @else
        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="60">Kode</th>
                    <th>Aspek</th>
                    <th width="100">Nilai</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aspek as $kolom => $info)
                <tr>
                    <td>{{ $info['kode'] }}</td>
                    <td>{{ $info['label'] }}</td>
                    <td class="text-center">{{ $penilaian->{$kolom} ?? '-' }}</td>
                    <td>
                        @php
                            $nilai = $penilaian->{$kolom};
                            $deskripsi = match($nilai) {
                                4 => 'Sangat Baik',
                                3 => 'Baik',
                                2 => 'Cukup',
                                1 => 'Kurang',
                                0 => 'Kurang',
                                default => '-',
                            };
                        @endphp
                        {{ $deskripsi }}
                    </td>
                </tr>
                @endforeach
                <tr class="table-active">
                    <th colspan="2">Nilai Total</th>
                    <th class="text-center">{{ $penilaian->nilai_total !== null ? number_format((float) $penilaian->nilai_total, 2, ',', '.') : '-' }}</th>
                    <td>{{ $penilaian->predikat }}</td>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
</div>

@if($penilaian->catatan)
<div class="card mt-3">
    <div class="card-header bg-warning text-dark">
        <strong><i class="bi bi-journal"></i> Catatan</strong>
    </div>
    <div class="card-body mb-0">
        {{ $penilaian->catatan }}
    </div>
</div>
@endif
@endsection
