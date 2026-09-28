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
                <th>No SK</th>
                <td>{{ $sk->no_sk ?: '-' }}</td>
            </tr>
            <tr>
                <th>No Tambahan</th>
                <td>{{ $sk->no_tambahan ?: '-' }}</td>
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
                <th>Tempat Lahir</th>
                <td>{{ $sk->tempat_lahir ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ optional($sk->tanggal_lahir)->format('d/m/Y') ?: '-' }}</td>
            </tr>
            <tr>
                <th>NIPY</th>
                <td>{{ $sk->nipy ?: '-' }}</td>
            </tr>
            <tr>
                <th>Gol Ruang</th>
                <td>{{ $sk->gol_ruang ?: '-' }}</td>
            </tr>
            <tr>
                <th>Status Kepegawaian</th>
                <td>{{ $sk->status_kepegawaian ?: '-' }}</td>
            </tr>
            <tr>
                <th>Unit Kerja</th>
                <td>{{ $sk->unit_kerja ?: '-' }}</td>
            </tr>
            <tr>
                <th>TMT</th>
                <td>{{ $sk->tmt ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>{{ optional($sk->tgl_mulai)->format('d/m/Y') ?: '-' }}</td>
            </tr>
            <tr>
                <th>Berlaku</th>
                <td>{{ $sk->berlaku ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Akhir</th>
                <td>{{ optional($sk->tanggal_akhir)->format('d/m/Y') ?: '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Ditetapkan</th>
                <td>{{ optional($sk->tanggal_ditetapkan)->format('d/m/Y') ?: '-' }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ optional($sk->created_at)->format('d/m/Y H:i') ?: '-' }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ optional($sk->updated_at)->format('d/m/Y H:i') ?: '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection