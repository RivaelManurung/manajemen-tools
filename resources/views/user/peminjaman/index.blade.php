@extends('user.layout.main')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Riwayat Transaksi Saya</h5>
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Buat Transaksi Baru
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Detail Barang</th>
                        <th>Storeman</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($riwayatTransaksi as $transaksi)
                    <tr>
                        <td><strong>{{ $transaksi->kode_transaksi }}</strong></td>
                        <td>
                            @if($transaksi->tipe == 'peminjaman')
                                <span class="badge bg-label-warning">Peminjaman</span>
                            @else
                                <span class="badge bg-label-success">Pengembalian</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y, H:i') }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach($transaksi->details as $detail)
                                    <li>{{ $detail->peralatan?->nama }} ({{ $detail->jumlah }} unit)</li>
                                @endforeach
                            </ul>
                        </td>
                        {{-- Kolom Storeman ditambahkan untuk info tambahan --}}
                        <td>{{ $transaksi->storeman?->nama ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Anda belum memiliki riwayat transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $riwayatTransaksi->links() }}
        </div>
    </div>
</div>
@endsection