{{-- @extends('admin.layout.main')

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
                        <th>Status / Aksi</th>
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
                            @if($transaksi->tipe == 'peminjaman')
                            @if($transaksi->pengembalian)
                            <span class="badge bg-label-success">Lunas</span>
                            @else
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#returnModal-{{ $transaksi->id }}">
                                Kembalikan
                            </button>
                            @endif
                            @else
                            <span class="text-muted">-</span>
                            @endif
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

@foreach($riwayatTransaksi->where('tipe', 'peminjaman') as $transaksi)
<div class="modal fade" id="returnModal-{{ $transaksi->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.kembalikan', $transaksi->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Anda akan mengembalikan semua barang dari transaksi <strong>{{ $transaksi->kode_transaksi
                            }}</strong>. Silakan pilih Storeman yang menerima.</p>
                    <div class="mb-3">
                        <label class="form-label">Storeman Penerima</label>
                        <select name="storeman_id" class="form-select" required>
                            <option value="">-- Pilih Storeman --</option>
                            @foreach(App\Models\Storeman::all() as $storeman)
                            <option value="{{ $storeman->id }}">{{ $storeman->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection --}}

@extends('admin.layout.main')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Notifikasi dari Session (Sudah ada) --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ✅ SOLUSI: TAMBAHKAN BLOK INI UNTUK MENAMPILKAN ERROR VALIDASI --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi Kesalahan!</strong> Mohon periksa input Anda:
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
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