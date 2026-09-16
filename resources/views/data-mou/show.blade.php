@extends('layouts.app')

@section('title', 'Detail Data MOU')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Data MOU</h3>
    <div>
        <a href="{{ route('data-mou.edit', $mou->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('data-mou.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card">
    <div class="card-header bg-info text-white">
        <strong>{{ $mou->nama }}</strong> <span class="text-light">({{ $mou->no_sk ?: '-' }})</span>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">ID</th>
                <td>{{ $mou->id }}</td>
            </tr>
            <tr>
                <th>No. SK</th>
                <td>{{ $mou->no_sk ?: '-' }}</td>
            </tr>
            <tr>
                <th>No. Tambahan</th>
                <td>{{ $mou->no_tambahan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Status Kepegawaian</th>
                <td>{{ $mou->status_kepegawaian ?: '-' }}{{ $mou->status_detail ? '/'.$mou->status_detail : '' }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $mou->nama ?: '-' }}</td>
            </tr>
            <tr>
                <th>Gelar</th>
                <td>{{ $mou->gelar ?: '-' }}</td>
            </tr>
            <tr>
                <th>Hari Kerja</th>
                <td>{{ $mou->hari_kerja ?: '-' }}</td>
            </tr>
            <tr>
                <th>Jam Kerja</th>
                <td>{{ $mou->jam_kerja ?: '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $mou->alamat ?: '-' }}</td>
            </tr>
            <tr>
                <th>Hari MOU</th>
                <td>{{ $mou->hari ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal MOU</th>
                <td>{{ $mou->tgl_mou ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tempat Lahir</th>
                <td>{{ $mou->tempat_lahir ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $mou->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td>{{ $mou->unit_kerja ?: '-' }}</td>
            </tr>
            <tr>
                <th>Gaji Pokok</th>
                <td>{{ $mou->gaji_pokok ? number_format((float) $mou->gaji_pokok, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Jabatan</th>
                <td>{{ $mou->tunjangan_jabatan ? number_format((float) $mou->tunjangan_jabatan, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Transport</th>
                <td>{{ $mou->tunjangan_transport ? number_format((float) $mou->tunjangan_transport, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Kinerja</th>
                <td>{{ $mou->tunjangan_kinerja ? number_format((float) $mou->tunjangan_kinerja, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Fungsional</th>
                <td>{{ $mou->tunjangan_fungsional ? number_format((float) $mou->tunjangan_fungsional, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>THP</th>
                <td>{{ $mou->thp ? number_format((float) $mou->thp, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Terbilang</th>
                <td>{{ $mou->terbilang ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>{{ $mou->tgl_mulai?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Berlaku</th>
                <td>{{ $mou->berlaku ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Akhir</th>
                <td>{{ $mou->tanggal_akhir?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Saksi 1</th>
                <td>{{ $mou->saksi1 ?: '-' }}</td>
            </tr>
            <tr>
                <th>Saksi 2</th>
                <td>{{ $mou->saksi2 ?: '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $mou->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $mou->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection