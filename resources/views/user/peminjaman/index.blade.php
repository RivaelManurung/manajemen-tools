@extends('admin.layout.main')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Riwayat Transaksi Saya</h5>
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Buat Peminjaman Baru
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Detail Barang</th>
                        <th>Tanggal</th>
                        <th>Status Pengembalian</th> {{-- Kolom baru untuk info --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayatTransaksi as $transaksi)
                    <tr>
                        <td>
                            @if($transaksi->tipe == 'peminjaman')
                            <span class="badge bg-label-warning">Peminjaman</span>
                            @else
                            <span class="badge bg-label-success">Pengembalian</span>
                            @endif
                        </td>
                        <td>
                            <ul>
                                @foreach($transaksi->details as $detail)
                                <li>{{ $detail->peralatan?->nama }} ({{ $detail->jumlah }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                        <td>
                            {{-- Di sini Anda bisa menambahkan logika untuk menampilkan tombol "Kembalikan"
                            jika barang dari transaksi peminjaman ini belum sepenuhnya dikembalikan.
                            Ini memerlukan logika kalkulasi yang lebih kompleks.
                            Untuk saat ini, link ke form pengembalian bisa diletakkan di header. --}}
                            <span class="text-muted">Completed</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Anda belum memiliki riwayat transaksi.</td>
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