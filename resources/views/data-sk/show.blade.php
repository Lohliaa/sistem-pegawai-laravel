@extends('layouts.app')

@section('title', 'Detail Data SK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-eye"></i> Detail Data SK</h3>
    <div>
        <a href="{{ route('data-sk.edit', $sk->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('data-sk.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
<hr>

<div class="card">
    <div class="card-header bg-info text-white">
        <strong>{{ $sk->nama }}</strong> <span class="text-light">({{ $sk->no_sk ?: '-' }})</span>
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="240">ID</th>
                <td>{{ $sk->id }}</td>
            </tr>
            <tr>
                <th>No. SK</th>
                <td>{{ $sk->no_sk ?: '-' }}</td>
            </tr>
            <tr>
                <th>Status Kepegawaian</th>
                <td>{{ $sk->status_kepegawaian ?: '-' }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $sk->nama ?: '-' }}</td>
            </tr>
            <tr>
                <th>Gelar</th>
                <td>{{ $sk->gelar ?: '-' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $sk->alamat ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tempat Lahir</th>
                <td>{{ $sk->tempat_lahir ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $sk->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td>{{ $sk->unit_kerja ?: '-' }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td>{{ $sk->jabatan ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>{{ $sk->tgl_mulai?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Gaji Pokok</th>
                <td>{{ $sk->gaji_pokok ? number_format((float) $sk->gaji_pokok, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Jabatan</th>
                <td>{{ $sk->tunjangan_jabatan ? number_format((float) $sk->tunjangan_jabatan, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Transport</th>
                <td>{{ $sk->tunjangan_transport ? number_format((float) $sk->tunjangan_transport, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Kinerja</th>
                <td>{{ $sk->tunjangan_kinerja ? number_format((float) $sk->tunjangan_kinerja, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Tunjangan Fungsional</th>
                <td>{{ $sk->tunjangan_fungsional ? number_format((float) $sk->tunjangan_fungsional, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>THP</th>
                <td>{{ $sk->thp ? number_format((float) $sk->thp, 0, ',', '.') : '-' }}</td>
            </tr>
            <tr>
                <th>Terbilang</th>
                <td>{{ $sk->terbilang ?: '-' }}</td>
            </tr>
            <tr>
                <th>Saksi 1</th>
                <td>{{ $sk->saksi1 ?: '-' }}</td>
            </tr>
            <tr>
                <th>Saksi 2</th>
                <td>{{ $sk->saksi2 ?: '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $sk->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $sk->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection