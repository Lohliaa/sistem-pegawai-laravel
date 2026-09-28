@extends('layouts.app')

@section('title', 'Pembaruan MoU')

@section('content')

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('data-mou.pembaruan') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Filter Bulan</label>
                <select name="bulan" class="form-select" onchange="this.form.submit()">
                    <option value="semua" {{ $bulan == 'semua' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Illuminate\Support\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <option value="semua" {{ $tahun == 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <a href="{{ route('data-mou.pembaruan.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Download Excel
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Status Kepegawaian</th>
                    <th>Unit Kerja</th>
                    <th>Tanggal MoU</th>
                    <th>Tanggal Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mous as $index => $mou)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mou->nama }}</td>
                    <td>{{ $mou->status_kepegawaian }}</td>
                    <td>{{ $mou->unit_kerja }}</td>
                    <td>{{ $mou->tgl_mou ? \Illuminate\Support\Carbon::parse($mou->tgl_mou)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $mou->tanggal_akhir ? \Illuminate\Support\Carbon::parse($mou->tanggal_akhir)->format('d-m-Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

