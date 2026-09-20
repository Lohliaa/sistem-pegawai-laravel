    <div class="row mb-2">
        <div class="col-12">
            <h5 class="text-secondary fw-bold mb-3 mt-2"><i class="bi bi-people"></i> Ringkasan Pegawai & Statistik</h5>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <!-- Total Pegawai -->
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Pegawai</h6>
                            <h2 class="display-6 fw-bold mb-0 mt-2">{{ $data['total_pegawai'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-people display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Users -->
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Jumlah Users</h6>
                            <h2 class="display-6 fw-bold mb-0 mt-2">{{ $data['total_users'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-person-gear display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jumlah Gender -->
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Jumlah Gender</h6>
                    <ul class="list-unstyled mb-0 small">
                        @forelse($data['pegawaiPerGender'] ?? [] as $gender => $count)
                            <li class="d-flex justify-content-between py-1 border-bottom border-light border-opacity-25">
                                <span>{{ $gender === 'L' ? 'Laki-laki' : ($gender === 'P' ? 'Perempuan' : 'Tidak Diatur') }}</span>
                                <span class="badge bg-light text-dark fw-bold">{{ $count }}</span>
                            </li>
                        @empty
                            <li>Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <!-- Jumlah Data BPI -->
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title mb-2">Jumlah Data BPI</h6>
                    <ul class="list-unstyled mb-0 small">
                        @forelse($data['pegawaiPerBpi'] ?? [] as $bpi => $count)
                            <li class="d-flex justify-content-between py-1 border-bottom border-dark border-opacity-10">
                                <span>{{ $bpi ?: 'Kosong' }}</span>
                                <span class="badge bg-dark text-white fw-bold">{{ $count }}</span>
                            </li>
                        @empty
                            <li>Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <!-- Jumlah Pegawai Antar Unit -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-building"></i> Jumlah Pegawai Antar Unit</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerUnit'] ?? [] as $unit => $count)
                                <tr>
                                    <td class="ps-3">{{ $unit ?: 'Tidak Ada Unit' }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Jumlah Antar Jabatan -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-briefcase"></i> Jumlah Pegawai Antar Jabatan</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerJabatan'] ?? [] as $jabatan => $count)
                                <tr>
                                    <td class="ps-3">{{ $jabatan ?: 'Tidak Ada Jabatan' }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Jumlah Status Kepegawaian -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-card-checklist"></i> Jumlah Status Kepegawaian</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerStatusKepegawaian'] ?? [] as $status => $count)
                                <tr>
                                    <td class="ps-3">{{ $status }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Jumlah Jenjang Kependidikan -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white py-2">
                    <strong><i class="bi bi-mortarboard"></i> Jumlah Jenjang Kependidikan</strong>
                </div>
                <div class="card-body p-0" style="max-height: 220px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-hover mb-0 w-100">
                        <tbody>
                            @forelse($data['pegawaiPerPendidikan'] ?? [] as $pendidikan => $count)
                                <tr>
                                    <td class="ps-3">{{ $pendidikan ?: 'Tidak Diisi' }}</td>
                                    <td width="100" class="text-end pe-3"><span class="badge bg-secondary">{{ $count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-2 text-muted">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
