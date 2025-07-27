@extends('admin.layout.main')

@section('title', 'Riwayat Transaksi Peminjaman')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Riwayat Transaksi Peralatan</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Detail Barang</th>
                        <th>User</th>
                        <th>Petugas (Storeman)</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($transaksis as $transaksi)
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
                            <ul class="list-unstyled">
                                @foreach($transaksi->details as $detail)
                                <li>{{ $detail->peralatan?->nama ?? 'N/A' }} ({{ $detail->jumlah }} unit)</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $transaksi->user?->fullname ?? 'N/A' }}</td>
                        <td>{{ $transaksi->storeman?->nama ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada riwayat transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- Tampilkan link pagination --}}
            {{ $transaksis->links() }}
        </div>
    </div>
</div>
@endsection