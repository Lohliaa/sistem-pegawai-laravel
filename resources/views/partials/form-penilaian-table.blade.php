@php
    $current = $current ?? [];
    $itemKeys = collect($items)
        ->where('type', 'item')
        ->pluck('key')
        ->values()
        ->all();
    $counter = 1;
@endphp

<div class="table-responsive">
    <table class="table table-sm table-bordered align-middle mb-0" id="tabel-penilaian">
        <thead class="table-dark">
            <tr>
                <th width="50">No</th>
                <th>Uraian</th>
                <th class="text-center" style="width: 120px;">Nilai (0-4)</th>
                <th style="width: 260px;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $row)
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
                        $key = $row['key'];
                        $currentNilai = $current[$key]['nilai'] ?? '';
                    @endphp
                    @if (!empty($row['sub']))
                        <tr class="table-warning">
                            <td class="text-center fw-bold">{{ $counter++ }}</td>
                            <td><span class="fw-semibold">{{ $row['uraian'] }}</span></td>
                            <td class="text-center fw-semibold" id="nilai-tampil-{{ $key }}">
                                @if ($currentNilai !== '' && $currentNilai !== null)
                                    {{ str_replace('.', ',', rtrim(rtrim(number_format((float) $currentNilai, 2, '.', ''), '0'), '.')) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <input type="text" name="catatan_baris[{{ $key }}]"
                                       value="{{ $current[$key]['catatan'] ?? '' }}"
                                       class="form-control form-control-sm catatan-item" maxlength="500"
                                       placeholder="Catatan baris (opsional)">
                            </td>
                        </tr>
                        @foreach($row['sub'] as $i => $subDef)
                            @php
                                $subNilai = $current[$key]['sub'][$i]['nilai'] ?? '';
                                $subCatatan = $current[$key]['sub'][$i]['catatan'] ?? '';
                            @endphp
                            <tr>
                                <td></td>
                                <td><div class="ps-4 small">{{ chr(97 + $i) }}. {{ $subDef['uraian'] }}</div></td>
                                <td class="text-center">
                                    <select name="nilai[{{ $key }}][sub][{{ $i }}]"
                                            class="form-select form-select-sm nilai-sub"
                                            data-item-key="{{ $key }}"
                                            aria-label="Nilai {{ $subDef['uraian'] }}">
                                        <option value="">-</option>
                                        @for ($v = 0; $v <= 4; $v++)
                                            <option value="{{ $v }}" @selected($subNilai !== '' && $subNilai !== null && (int) $subNilai === $v)>{{ $v }}</option>
                                        @endfor
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="catatan_poin[{{ $key }}][{{ $i }}]"
                                           value="{{ $subCatatan }}"
                                           class="form-control form-control-sm catatan-item" maxlength="500"
                                           placeholder="Catatan poin (opsional)">
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center fw-bold">{{ $counter++ }}</td>
                            <td><span class="fw-semibold">{{ $row['uraian'] }}</span></td>
                            <td class="text-center">
                                <select name="nilai[{{ $key }}]"
                                        class="form-select form-select-sm nilai-simple"
                                        data-item-key="{{ $key }}"
                                        aria-label="Nilai {{ $row['uraian'] }}">
                                    <option value="">-</option>
                                    @for ($v = 0; $v <= 4; $v++)
                                        <option value="{{ $v }}" @selected($currentNilai !== '' && $currentNilai !== null && (int) $currentNilai === $v)>{{ $v }}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <input type="text" name="catatan_baris[{{ $key }}]" value="{{ $current[$key]['catatan'] ?? '' }}"
                                       class="form-control form-control-sm catatan-item" maxlength="500"
                                       placeholder="Catatan baris (opsional)">
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
</div>
<p class="text-muted small mt-2 mb-0">
    <i class="bi bi-info-circle"></i>
    Skala nilai: 0-1 = Kurang, 2 = Cukup, 3 = Baik, 4 = Sangat Baik. Untuk uraian yang memiliki poin
    (a., b., dst.) nilai uraian otomatis dihitung rata-rata dari poin-poin yang diisi. Nilai boleh dikosongkan bila tidak dinilai.
</p>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabel = document.getElementById('tabel-penilaian');
    if (!tabel) return;

    const itemKeys = @json($itemKeys);
    const items = @json($items);
    const kompetensiWeights = @json(\App\Models\PenilaianKinerja::getKompetensiWeights($kategori ?? 'pegawai'));

    function nilaiItem(key) {
        const selInduk = tabel.querySelector('select.nilai-simple[data-item-key="' + key + '"]');
        if (selInduk) {
            return selInduk.value === '' ? null : parseInt(selInduk.value, 10);
        }

        const subs = tabel.querySelectorAll('select.nilai-sub[data-item-key="' + key + '"]');
        if (subs.length === 0) {
            return null;
        }

        let jumlah = 0;
        let banyak = 0;

        subs.forEach(function (sel) {
            if (sel.value !== '') {
                jumlah += parseInt(sel.value, 10);
                banyak++;
            }
        });

        if (banyak === 0) {
            return null;
        }

        return Math.round((jumlah / banyak) * 100) / 100;
    }

    function muatTampilan(key) {
        const el = document.getElementById('nilai-tampil-' + key);
        if (!el) return;

        const n = nilaiItem(key);
        el.textContent = n === null ? '-' : String(n).replace('.', ',');
    }

    function formatAngka(v) {
        return v.toFixed(2).replace('.', ',');
    }

    function hitungTotal() {
        let currentSection = '';
        let currentSubSection = '';
        let kompetensiItems = {};
        let komitmenSubItems = {keislaman: [], pengembangan_diri: [], kedisiplinan: []};
        let kinerjaSubItems = {okr_individu: [], kerja_harian: []};

        items.forEach(function (row) {
            const type = row.type || '';
            if (type === 'section') {
                const labelUpper = (row.label || '').toUpperCase();
                if (labelUpper.indexOf('KOMPETENSI') !== -1) currentSection = 'kompetensi';
                else if (labelUpper.indexOf('KOMITMEN') !== -1) currentSection = 'komitmen';
                else if (labelUpper.indexOf('KINERJA') !== -1) currentSection = 'kinerja';
                return;
            }
            if (type === 'sub') {
                const labelUpper = (row.label || '').toUpperCase();
                if (labelUpper.indexOf('KEISLAMAN') !== -1) currentSubSection = 'keislaman';
                else if (labelUpper.indexOf('PENGEMBANGAN DIRI') !== -1) currentSubSection = 'pengembangan_diri';
                else if (labelUpper.indexOf('KEDISIPLINAN') !== -1) currentSubSection = 'kedisiplinan';
                return;
            }
            if (type !== 'item') return;

            const key = row.key;
            const n = nilaiItem(key);
            muatTampilan(key);
            if (n === null) return;

            if (currentSection === 'kompetensi') {
                kompetensiItems[key] = n;
            } else if (currentSection === 'komitmen') {
                if (komitmenSubItems.hasOwnProperty(currentSubSection)) komitmenSubItems[currentSubSection].push(n);
            } else if (currentSection === 'kinerja') {
                if (kinerjaSubItems.hasOwnProperty(key)) kinerjaSubItems[key].push(n);
            }
        });

        // 1. KOMPETENSI
        let totalKompetensiRaw = 0, totalWeightK = 0;
        for (const k in kompetensiWeights) {
            if (kompetensiItems.hasOwnProperty(k)) {
                totalKompetensiRaw += kompetensiItems[k] * kompetensiWeights[k];
                totalWeightK += kompetensiWeights[k];
            }
        }
        let totalKompetensi = totalWeightK > 0 ? 0.25 * (totalKompetensiRaw / totalWeightK) : 0;

        // 2. KOMITMEN (35%)
        let sumKeislaman = komitmenSubItems.keislaman.reduce(function(a, b) { return a + b; }, 0);
        let sumPengembanganDiri = komitmenSubItems.pengembangan_diri.reduce(function(a, b) { return a + b; }, 0);
        let sumKedisiplinan = komitmenSubItems.kedisiplinan.reduce(function(a, b) { return a + b; }, 0);

        let nilaiKeislaman = sumKeislaman * 0.40;
        let nilaiPengembanganDiri = sumPengembanganDiri * 0.30;
        let nilaiKedisiplinan = sumKedisiplinan * 0.30;

        let nilaiKomitmen = nilaiKeislaman + nilaiPengembanganDiri + nilaiKedisiplinan;
        let totalKomitmen = 0.35 * nilaiKomitmen;

        // 3. KINERJA (40%)
        const kinW = {okr_individu: 0.65, kerja_harian: 0.35};
        let okrAvg = kinerjaSubItems.okr_individu.length > 0 ? kinerjaSubItems.okr_individu.reduce(function(a, b) { return a + b; }, 0) / kinerjaSubItems.okr_individu.length : 0;
        let kerjaAvg = kinerjaSubItems.kerja_harian.length > 0 ? kinerjaSubItems.kerja_harian.reduce(function(a, b) { return a + b; }, 0) / kinerjaSubItems.kerja_harian.length : 0;
        let totalKinerja = 0.40 * (okrAvg * kinW.okr_individu + kerjaAvg * kinW.kerja_harian);

        let totalSeluruhAspek = totalKompetensi + totalKomitmen + totalKinerja;
        
        let sumNilai = 0;
        let countNilai = 0;
        itemKeys.forEach(function(key) {
            const n = nilaiItem(key);
            if (n !== null) {
                sumNilai += n;
                countNilai++;
            }
        });

        const elTotalKompetensi = document.getElementById('total-kompetensi');
        if (elTotalKompetensi) elTotalKompetensi.textContent = formatAngka(totalKompetensi);

        const elTotalKeislaman = document.getElementById('total-keislaman');
        if (elTotalKeislaman) elTotalKeislaman.textContent = formatAngka(nilaiKeislaman);

        const elTotalPengembanganDiri = document.getElementById('total-pengembangan-diri');
        if (elTotalPengembanganDiri) elTotalPengembanganDiri.textContent = formatAngka(nilaiPengembanganDiri);

        const elTotalKedisiplinan = document.getElementById('total-kedisiplinan');
        if (elTotalKedisiplinan) elTotalKedisiplinan.textContent = formatAngka(nilaiKedisiplinan);

        const elTotalKomitmen = document.getElementById('total-komitmen');
        if (elTotalKomitmen) elTotalKomitmen.textContent = formatAngka(totalKomitmen);

        const elTotalKinerja = document.getElementById('total-kinerja');
        if (elTotalKinerja) elTotalKinerja.textContent = formatAngka(totalKinerja);

        const elTotalSeluruhAspek = document.getElementById('total-seluruh-aspek');
        if (elTotalSeluruhAspek) elTotalSeluruhAspek.textContent = formatAngka(totalSeluruhAspek);

        const elNilaiKeseluruhan = document.getElementById('nilai-keseluruhan');
        if (elNilaiKeseluruhan) {
            // Nilai Keseluruhan = ((Total Kompetensi + Total Komitmen + Total Kinerja) / Total Seluruh Nilai Aspek) * 100
            const nilaiKeseluruhan = totalSeluruhAspek > 0 ? (totalSeluruhAspek / totalSeluruhAspek) * 100 : 0;
            elNilaiKeseluruhan.textContent = formatAngka(nilaiKeseluruhan);
        }
    }

    tabel.querySelectorAll('select[data-item-key]').forEach(function (sel) {
        sel.addEventListener('change', hitungTotal);
    });

    tabel.querySelectorAll('.catatan-item').forEach(function (input) {
        input.addEventListener('input', hitungTotal);
    });

    hitungTotal();
});
</script>
@endpush