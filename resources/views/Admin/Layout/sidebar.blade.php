<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/Logo.png') }}" alt="Logo" width="30" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Tools Mng.</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- =============================================== --}}
        {{-- | MENU UNTUK SEMUA USER | --}}
        {{-- =============================================== --}}
        <li class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>
        {{-- <li class="menu-item {{ Request::is('admin/transaksi/create*') ? 'active' : '' }}">
            <a href="{{ route('transaksi.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div>Buat Transaksi</div>
            </a>
        </li> --}}

        {{-- =============================================== --}}
        {{-- | MENU KHUSUS ADMIN | --}}
        {{-- =============================================== --}}
        {{-- PERBAIKAN: Logika diubah hanya untuk peran 'admin' --}}
        @if (Auth::user()->peran === 'admin')
        <li class="menu-item {{ Request::is('admin/transaksi') && !Request::is('transaksi/create') ? 'active' : '' }}">
            <a href="{{ route('admin.transaksi.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div>Riwayat Transaksi</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data Master</span>
        </li>
        <li class="menu-item {{ Request::is('admin/peralatan*') ? 'active' : '' }}">
            <a href="{{ route('admin.peralatan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wrench"></i>
                <div>Manajemen Peralatan</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div>Manajemen Pengguna</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/job-titles*') ? 'active' : '' }}">
            <a href="{{ route('admin.job-titles.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase"></i>
                <div>Manajemen Job Title</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/departments*') ? 'active' : '' }}">
            <a href="{{ route('admin.departments.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-business"></i>
                <div>Manajemen Departemen</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/storemen*') ? 'active' : '' }}">
            <a href="{{ route('admin.storeman.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div>Manajemen Storeman</div>
            </a>
        </li>
        @endif
    </ul>
</aside>