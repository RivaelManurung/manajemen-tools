@extends('admin.layout.main')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Welcome Card --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 👋</h5>
                            <p class="mb-4">Anda login sebagai <strong>{{ ucfirst(Auth::user()->peran) }}</strong>. Berikut ringkasan data dari sistem manajemen peralatan.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="140" alt="Man with laptop" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/briefcase-alt-2.png') }}" alt="Peralatan" class="rounded" />
                        </div>
                    </div>
                    <span>Total Peralatan</span>
                    <h4 class="card-title mb-1">{{ $totalPeralatan }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/check-circle.png') }}" alt="Tersedia" class="rounded" />
                        </div>
                    </div>
                    <span>Peralatan Tersedia</span>
                    <h4 class="card-title text-success mb-1">{{ $peralatanTersedia }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/time-five.png') }}" alt="Dipinjam" class="rounded" />
                        </div>
                    </div>
                    <span>Peralatan Dipinjam</span>
                    <h4 class="card-title text-warning mb-1">{{ $peralatanDipinjam }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/users-alt.png') }}" alt="Pengguna" class="rounded" />
                        </div>
                    </div>
                    <span>Total Pengguna</span>
                    <h4 class="card-title mb-1">{{ $totalPengguna }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Tabel Riwayat Peminjaman Terbaru --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Riwayat Peminjaman Terbaru</h5>
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Peralatan</th>
                                <th>Mekanik</th>
                                <th>Storeman</th>
                                <th>Waktu Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logTerbaru as $log)
                            <tr>
                                <td><strong>{{ $log->peralatan->nama }}</strong></td>
                                <td>{{ $log->mekanik->name }}</td>
                                <td>{{ $log->storeman->name }}</td>
                                <td>{{ $log->waktu_pinjam->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($log->waktu_kembali)
                                        <span class="badge bg-label-success">Sudah Kembali</span>
                                    @else
                                        <span class="badge bg-label-danger">Belum Kembali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection