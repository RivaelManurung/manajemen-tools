<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title', 'Dashboard') | Aplikasi Peminjaman</title>

    <meta name="description" content="Aplikasi Peminjaman Tools" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    
    @stack('styles')
</head>

<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            {{-- Bagian Kiri Navbar (Logo/Brand) --}}
            <a class="navbar-brand" href="{{ route('user.peminjaman.index') }}">
                <i class='bx bxs-wrench'></i>
                Manajemen Tools
            </a>

            {{-- Bagian Kanan Navbar --}}
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    {{-- Dropdown Profil Pengguna --}}
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bxs-user-circle me-1'></i>
                            {{ Auth::user()->fullname }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class='bx bx-log-out me-2'></i>Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    {{-- Konten utama dari setiap halaman akan dirender di sini --}}
    <main>
        @yield('content')
    </main>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    @stack('scripts')

</body>
</html>