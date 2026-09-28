@extends('layouts.app')

@section('title', 'Pembaruan SK')

@section('content')

@include('partials.errors')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('data-sk.pembaruan') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Periode Pembaruan SK</label>
                <select name="periode" class="form-select" onchange="this.form.submit()">
                    <option value="semua" {{ $periode === 'semua' ? 'selected' : '' }}>Semua Periode</option>
                    <option value="januari" {{ $periode === 'januari' ? 'selected' : '' }}>Pembaruan Januari</option>
                    <option value="juli" {{ $periode === 'juli' ? 'selected' : '' }}>Pembaruan Juli</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun TMT</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    <option value="semua" {{ $tahun === 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('data-sk.pembaruan.export', ['periode' => $periode, 'tahun' => $tahun]) }}" class="btn btn-success">
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
                    <th width="60">No</th>
                    <th>Nama</th>
                    <th>TMT</th>
                    <th>Unit</th>
                    <th>Status Kepegawaian</th>
                    <th>Periode Pembaruan SK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sks as $index => $sk)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sk->nama }}</td>
                    <td>{{ optional($sk->tmt)->format('d-m-Y') ?: '-' }}</td>
                    <td>{{ $sk->unit_kerja }}</td>
                    <td>{{ $sk->status_kepegawaian }}</td>
                    <td>{{ $sk->periode_pembaruan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data pembaruan SK</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

