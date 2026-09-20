@php
    $ketuaYayasan = \App\Models\PejabatYayasan::where('jabatan', 'LIKE', '%Ketua Yayasan%')->where('status', 'aktif')->first();
@endphp
@php
    $kabidSdm = \App\Models\PejabatYayasan::where('jabatan', 'LIKE', '%SDM%')->where('status', 'aktif')->first();
@endphp
@foreach($laporans as $penilaian)
    @include('print.penilaian', ['penilaian' => $penilaian, 'ketuaYayasan' => $ketuaYayasan, 'kabidSdm' => $kabidSdm])
    @if (!$loop->last)
        <div style='page-break-after: always;'></div>
    @endif
@endforeach
