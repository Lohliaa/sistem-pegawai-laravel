<div class="sidebar" id="sidebar">
    <div>
        <div class="brand">
            <div>
                <h4 class="mb-0"><i class="bi bi-building"></i>SIGATRA</h4>
                <small></small>
            </div>
            <button class="btn btn-sm btn-outline-light d-md-none" id="closeSidebarBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    <div class="flex-grow-1 overflow-auto" style="min-height: 0;">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-house"></i> Dashboard
    </a>

    <!-- Pengajuan Menu -->
    <div class="section-title mt-3 mb-2" style="color: #95a5a6; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Pengajuan</div>
    @if(auth()->user()->role == 'admin')
        <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Manajemen Pengajuan
        </a>
    @elseif(auth()->user()->role == 'staf')
        <a href="{{ route('pengajuan.create') }}" class="{{ request()->routeIs('pengajuan.create') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-plus"></i> Buat Pengajuan
        </a>
        <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.index') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i> Daftar Pengajuan
        </a>
    @elseif(in_array(auth()->user()->role, ['kanit', 'kabid']))
        <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
            <i class="bi bi-check-circle"></i> Persetujuan Pengajuan
        </a>
    @endif

    <!-- Admin Menu -->
    @if(auth()->user()->role == 'admin')
    <div class="section-title mt-3 mb-2" style="color: #95a5a6; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Admin</div>
    <a href="{{ route('profile-pegawai.index') }}" class="{{ request()->routeIs('profile-pegawai.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> Profile Pegawai
    </a>
    <a href="{{ route('manajemen-user.index') }}" class="{{ request()->routeIs('manajemen-user.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Manajemen User
    </a>
    <a href="{{ route('data-mou.index') }}" class="{{ request()->routeIs('data-mou.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-ruled"></i> Data MOU
    </a>
    <a href="{{ route('data-sk.index') }}" class="{{ request()->routeIs('data-sk.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> Data SK
    </a>
    @endif

    <!-- Penilaian Kinerja Menu (Admin, Kanit, Kabid) -->
    @if(in_array(auth()->user()->role, ['admin', 'kanit', 'kabid']))
    @php
        $isPenilaianActive = request()->routeIs('kinerja-status.*') || 
                             request()->routeIs('kinerja-periode.*') || 
                             request()->routeIs('kinerja-pejabat.*') || 
                             request()->routeIs('kinerja-bahan.*') || 
                             request()->routeIs('form-penilaian.*') || 
                             request()->routeIs('laporan-penilaian.*');
    @endphp
    
    <a href="#penilaianKinerjaSubmenu" data-bs-toggle="collapse" class="d-flex align-items-center justify-content-between {{ $isPenilaianActive ? 'active' : '' }}" aria-expanded="{{ $isPenilaianActive ? 'true' : 'false' }}" style="cursor: pointer;">
        <span><i class="bi bi-clipboard-data"></i> Penilaian Kinerja</span>
        <i class="bi bi-chevron-down small"></i>
    </a>
    
    <div class="collapse {{ $isPenilaianActive ? 'show' : '' }}" id="penilaianKinerjaSubmenu">
        <div class="ps-2 py-1" style="background: rgba(0,0,0,0.15); border-radius: 5px; margin-top: 2px; margin-bottom: 5px;">
            <a href="{{ route('kinerja-status.index') }}" class="{{ request()->routeIs('kinerja-status.*') ? 'active' : '' }}" style="font-size: 0.9rem; padding: 8px 12px;">
                <i class="bi bi-person-check"></i> Status
            </a>
            <a href="{{ route('kinerja-periode.index') }}" class="{{ request()->routeIs('kinerja-periode.*') ? 'active' : '' }}" style="font-size: 0.9rem; padding: 8px 12px;">
                <i class="bi bi-calendar3"></i> Periode
            </a>
            <a href="{{ route('kinerja-pejabat.index') }}" class="{{ request()->routeIs('kinerja-pejabat.*') ? 'active' : '' }}" style="font-size: 0.9rem; padding: 8px 12px;">
                <i class="bi bi-award"></i> Pejabat
            </a>
            <a href="{{ route('kinerja-bahan.index') }}" class="{{ request()->routeIs('kinerja-bahan.*') ? 'active' : '' }}" style="font-size: 0.9rem; padding: 8px 12px;">
                <i class="bi bi-file-earmark-text"></i> Bahan Penilaian
            </a>
            <!-- Form Penilaian Submenu -->
            @php $isFormActive = request()->routeIs('form-penilaian.*'); @endphp
            <a href="#formPenilaianSubmenu" data-bs-toggle="collapse" class="d-flex align-items-center justify-content-between {{ $isFormActive ? 'active' : '' }}" aria-expanded="{{ $isFormActive ? 'true' : 'false' }}" style="font-size: 0.9rem; padding: 8px 12px; cursor: pointer;">
                <span><i class="bi bi-clipboard-check"></i> Form Penilaian</span>
                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isFormActive ? 'show' : '' }}" id="formPenilaianSubmenu">
                <div class="ps-2 py-1" style="background: rgba(0,0,0,0.2); border-radius: 5px; margin-top: 2px; margin-bottom: 2px;">
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'pegawai']) }}" class="{{ request()->routeIs('form-penilaian.*') && request('kategori', 'pegawai') == 'pegawai' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-person"></i> Pegawai
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'guru-alquran']) }}" class="{{ request('kategori') == 'guru-alquran' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-book"></i> Guru Al Qur'an
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'guru-non-alquran']) }}" class="{{ request('kategori') == 'guru-non-alquran' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-journal-text"></i> Guru Non Al Qur'an
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'wali-kelas-reguler']) }}" class="{{ request('kategori') == 'wali-kelas-reguler' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-people"></i> Wali Kelas Reguler
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'wali-kelas-icp']) }}" class="{{ request('kategori') == 'wali-kelas-icp' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-people-fill"></i> Wali Kelas ICP
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'koordinator-jenjang']) }}" class="{{ request('kategori') == 'koordinator-jenjang' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-diagram-3"></i> Koordinator Jenjang
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'koordinator-alquran']) }}" class="{{ request('kategori') == 'koordinator-alquran' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-journal-bookmark"></i> Koordinator Al Qur'an
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'leader']) }}" class="{{ request('kategori') == 'leader' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-star"></i> Leader
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'musyrifah']) }}" class="{{ request('kategori') == 'musyrifah' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-shield-check"></i> Musyrif/ah
                    </a>
                    <a href="{{ route('form-penilaian.index', ['kategori' => 'cs']) }}" class="{{ request('kategori') == 'cs' ? 'active' : '' }}" style="font-size: 0.85rem; padding: 6px 10px;">
                        <i class="bi bi-tools"></i> CS
                    </a>
                </div>
            </div>
            <a href="{{ route('laporan-penilaian.index') }}" class="{{ request()->routeIs('laporan-penilaian.*') ? 'active' : '' }}" style="font-size: 0.9rem; padding: 8px 12px;">
                <i class="bi bi-bar-chart"></i> Laporan
            </a>
        </div>
    </div>
    @endif

    <hr style="border-color: #7f8c8d; opacity: 0.4; margin-top: 30px;">
    </div>

    <div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="padding: 10px 15px; color: #e74c3c !important;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>
