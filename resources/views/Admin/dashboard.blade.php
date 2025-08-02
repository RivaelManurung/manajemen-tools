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
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->fullname }}! 👋</h5>
                            <p class="mb-4">Anda login sebagai <strong>{{ ucfirst(Auth::user()->peran) }}</strong>.
                                Berikut ringkasan data dari sistem manajemen peralatan.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="140"
                                alt="Man with laptop" />
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
                    <span class="d-block">Total Unit Peralatan</span>
                    <h4 class="card-title mb-1">{{ $totalUnitPeralatan }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span>Stok Tersedia</span>
                    <h4 class="card-title text-success mb-1">{{ $stokTersedia }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span>Unit Dipinjam</span>
                    <h4 class="card-title text-warning mb-1">{{ $stokDipinjam }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span>Total Pengguna</span>
                    <h4 class="card-title mb-1">{{ $totalPengguna }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris untuk Grafik --}}
    <div class="row">
        <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Status Peralatan</h5>
                </div>
                <div class="card-body">
                    <div id="donutChart"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-8 col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Tren Transaksi (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div id="barChart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Transaksi Terbaru --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Transaksi Terbaru</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tipe</th>
                                <th>Peralatan</th>
                                <th>User</th>
                                <th>Storeman</th>
                                <th>Waktu Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksiTerbaru as $transaksi)
                            <tr>
                                <td>
                                    @if($transaksi->tipe == 'peminjaman')
                                    <span class="badge bg-label-warning">Peminjaman</span>
                                    @else
                                    <span class="badge bg-label-success">Pengembalian</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($transaksi->details as $detail)
                                    <div>{{ $detail->peralatan->nama }} ({{ $detail->jumlah }})</div>
                                    @endforeach
                                </td>
                                <td>{{ $transaksi->user->fullname }}</td>
                                <td>{{ $transaksi->storeman?->nama ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada transaksi.</td>
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


@push('scripts')
<script>
    $(function() {
        // 1. Konfigurasi Grafik Donut (dengan perbaikan legenda)
        const donutChartEl = document.querySelector('#donutChart');
        if (donutChartEl) {
            const donutChartOptions = {
                chart: {
                    type: 'donut',
                    height: 350
                },
                series: [{{ $stokTersedia }}, {{ $stokDipinjam }}],
                labels: ['Tersedia', 'Dipinjam'],
                colors: [config.colors.success, config.colors.warning],
                stroke: {
                    width: 5,
                    colors: ['transparent']
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        return opts.w.config.series[opts.seriesIndex];
                    }
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    offsetY: 10
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const donutChart = new ApexCharts(donutChartEl, donutChartOptions);
            donutChart.render();
        }

        // 2. Konfigurasi Grafik Batang (dengan perbaikan label)
        const barChartEl = document.querySelector('#barChart');
        if (barChartEl) {
            const barChartOptions = {
                chart: {
                    type: 'bar',
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Peminjaman',
                    data: {!! json_encode(array_values($dataPeminjaman)) !!}
                }, {
                    name: 'Pengembalian',
                    data: {!! json_encode(array_values($dataPengembalian)) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($chartLabels) !!},
                    labels: {
                        style: {
                            colors: config.colors.axisColor
                        },
                        offsetY: 5
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: config.colors.axisColor
                        }
                    },
                    tickAmount: 5,
                    forceNiceScale: true,
                    decimalsInFloat: 0
                },
                colors: [config.colors.warning, config.colors.success],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 4,
                        startingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                grid: {
                    borderColor: config.colors.borderColor
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: config.colors.axisColor
                    }
                },
            };

            const barChart = new ApexCharts(barChartEl, barChartOptions);
            barChart.render();
        }
    });
</script>
@endpush