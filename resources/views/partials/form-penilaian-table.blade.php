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
<tr class="table-active">
                <th colspan="2" class="text-end">Total (Jumlah &amp; Rata-rata)</th>
                <td class="text-center">
                    <div>Jumlah: <strong id="total-jumlah">0,00</strong></div>
                    <div>Rata-rata: <strong id="total-rata">0,00</strong></div>
                </td>
                <td></td>
            </tr>
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
    const elJumlah = document.getElementById('total-jumlah');
    const elRata = document.getElementById('total-rata');

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
        let jumlah = 0;
        let count = 0;

        itemKeys.forEach(function (key) {
            const n = nilaiItem(key);
            muatTampilan(key);

            if (n !== null) {
                jumlah += n;
                count++;
            }
        });

        elJumlah.textContent = formatAngka(Math.round(jumlah * 100) / 100);
        elRata.textContent = count > 0 ? formatAngka(jumlah / count) : '0,00';
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