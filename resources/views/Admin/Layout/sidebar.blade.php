<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Ganti dengan logo Anda --}}
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
        {{-- | MENU UNTUK SEMUA ROLE | --}}
        {{-- =============================================== --}}

        <li class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        {{-- Hanya tampilkan menu ini untuk admin dan storeman --}}
        @if (in_array(Auth::user()->peran, ['admin', 'storeman']))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>
        <li class="menu-item {{ Request::is('admin/borrow*') ? 'active' : '' }}">
            <a href="{{ route('admin.borrow.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="Peminjaman">Peminjaman & Pengembalian</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/logs*') ? 'active' : '' }}">
            <a href="{{ route('admin.logs.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Log Peminjaman">Log Peminjaman</div>
            </a>
        </li>
        @endif


        {{-- =============================================== --}}
        {{-- | MENU KHUSUS ADMIN | --}}
        {{-- =============================================== --}}
        @if (Auth::user()->peran === 'admin')
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data Master</span>
        </li>
        <li class="menu-item {{ Request::is('admin/peralatan*') ? 'active' : '' }}">
            <a href="{{ route('admin.peralatan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wrench"></i>
                <div data-i18n="Manajemen Peralatan">Manajemen Peralatan</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Manajemen User">Manajemen User</div>
            </a>
        </li>
        @endif
        {{-- Hanya tampilkan menu ini untuk admin dan storeman --}}
        {{-- @if (in_array(Auth::user()->peran, ['admin', 'storeman']))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>
        <li class="menu-item {{ Request::is('admin/borrow*') ? 'active' : '' }}">
            <a href="{{ route('admin.borrow.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="Peminjaman">Peminjaman & Pengembalian</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/logs*') ? 'active' : '' }}">
            <a href="{{ route('admin.logs.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Log Peminjaman">Log Peminjaman</div>
            </a>
        </li>
        @endif --}}

    </ul>
</aside>