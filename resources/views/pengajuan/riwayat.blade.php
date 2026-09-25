@extends('layouts.app')

@section('title', 'Riwayat Pengajuan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Riwayat Pengajuan</h2>
</div>
<hr>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tipe</th>
                    <th>Nama</th>
                    <th>Unit</th>
                    <th>Pimpinan Atasan</th>
                    <th>TMT</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge bg-secondary">{{ $p->tipe_pengajuan }}</span></td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ $p->pimpinan_atasan }}</td>
                    <td>{{ $p->tanggal_tmt->format('d/m/Y') }}</td>
                    <td>
                        @if($p->status == 'approved_kanit')
                            <span class="badge bg-info">Approved Kanit</span>
                        @elseif($p->status == 'approved_kabid')
                            <span class="badge bg-success">Approved Kabid</span>
                        @elseif($p->status == 'rejected')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning">{{ $p->status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pengajuan.show', $p->id) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada riwayat pengajuan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
