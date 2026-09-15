<div class="sidebar">
    <div class="brand">
        <h4><i class="bi bi-building"></i> SIPS</h4>
        <small>Sistem Informasi Pegawai</small>
    </div>

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
    <div class="section-title mt-3 mb-2" style="color: #95a5a6; font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">Penilaian Kinerja</div>
    <a href="{{ route('kinerja-status.index') }}" class="{{ request()->routeIs('kinerja-status.*') ? 'active' : '' }}">
        <i class="bi bi-person-check"></i> Status
    </a>
    <a href="{{ route('kinerja-periode.index') }}" class="{{ request()->routeIs('kinerja-periode.*') ? 'active' : '' }}">
        <i class="bi bi-calendar3"></i> Periode
    </a>
    <a href="{{ route('kinerja-pejabat.index') }}" class="{{ request()->routeIs('kinerja-pejabat.*') ? 'active' : '' }}">
        <i class="bi bi-award"></i> Pejabat
    </a>
    <a href="{{ route('kinerja-bahan.index') }}" class="{{ request()->routeIs('kinerja-bahan.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> Bahan Penilaian
    </a>
    <a href="{{ route('form-penilaian.index') }}" class="{{ request()->routeIs('form-penilaian.*') ? 'active' : '' }}">
        <i class="bi bi-clipboard-check"></i> Form Penilaian
    </a>
    <a href="{{ route('laporan-penilaian.index') }}" class="{{ request()->routeIs('laporan-penilaian.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart"></i> Laporan
    </a>
    @endif

    <hr style="border-color: #7f8c8d; opacity: 0.4; margin-top: 30px;">

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="padding: 10px 15px; color: #e74c3c !important;">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>
