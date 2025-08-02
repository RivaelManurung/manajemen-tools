@extends('admin.layout.main')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Riwayat Semua Transaksi</h5>
        </div>

        <div class="card-body border-bottom">
            <form action="{{ route('admin.transaksi.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Filter Cepat</label>
                    <select name="filter" onchange="this.form.submit()" class="form-select">
                        <option value="">Semua</option>
                        <option value="harian" @if(request('filter')=='harian' ) selected @endif>Hari Ini</option>
                        <option value="mingguan" @if(request('filter')=='mingguan' ) selected @endif>Minggu Ini</option>
                        <option value="bulanan" @if(request('filter')=='bulanan' ) selected @endif>Bulan Ini</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Storeman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
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
                        <td>{{ $transaksi->user?->fullname ?? 'N/A' }}</td>
                        <td>{{ $transaksi->storeman?->nama ?? '-' }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info view-details" data-bs-toggle="modal"
                                data-bs-target="#detailModal"
                                data-url="{{ route('admin.transaksi.show', $transaksi->id) }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            {{ $transaksis->links() }}
            <a href="{{ route('admin.transaksi.exportPDF', request()->query()) }}" class="btn btn-success">
                <i class="bx bxs-file-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalTitle">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <p class="text-center">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const detailModal = document.getElementById('detailModal');
    detailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const modalTitle = detailModal.querySelector('.modal-title');
        const modalBody = detailModal.querySelector('.modal-body');
        
        modalTitle.textContent = 'Detail Transaksi';
        modalBody.innerHTML = '<p class="text-center">Memuat data...</p>';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                modalTitle.textContent = `Detail Transaksi: ${data.kode_transaksi}`;
                
                let detailsHtml = `
                    <p><strong>User:</strong> ${data.user.fullname}</p>
                    <p><strong>Storeman:</strong> ${data.storeman ? data.storeman.nama : '-'}</p>
                    <p><strong>Tanggal:</strong> ${new Date(data.tanggal_transaksi).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' })}</p>
                    <p><strong>Tipe:</strong> ${data.tipe}</p>
                    <h6 class="mt-4">Barang yang Ditransaksikan:</h6>
                    <table class="table">
                        <thead>
                            <tr><th>Nama Barang</th><th>Jumlah</th><th>Kondisi</th></tr>
                        </thead>
                        <tbody>
                `;

                data.details.forEach(detail => {
                    detailsHtml += `
                        <tr>
                            <td>${detail.peralatan.nama}</td>
                            <td>${detail.jumlah}</td>
                            <td>${data.tipe === 'pengembalian' ? (detail.kondisi ? detail.kondisi.replace('_', ' ') : '-') : '-'}</td>
                        </tr>
                    `;
                });

                detailsHtml += '</tbody></table>';
                modalBody.innerHTML = detailsHtml;
            })
            .catch(error => {
                modalBody.innerHTML = '<p class="text-center text-danger">Gagal memuat data.</p>';
                console.error('Fetch Error:', error);
            });
    });
});
    </script>
    @endpush