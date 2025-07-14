@extends('admin.layout.main')

@section('title', 'Log Riwayat Peminjaman')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Log Riwayat Peminjaman Peralatan</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th>Peralatan</th>
                        <th>Mekanik</th>
                        <th>Storeman</th>
                        <th>Status</th>
                        <th>Kondisi Kembali</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->waktu_pinjam->format('d M Y, H:i') }}</td>
                        <td>
                            @if($log->waktu_kembali)
                                {{ $log->waktu_kembali->format('d M Y, H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td><strong>{{ $log->peralatan->nama ?? 'N/A' }}</strong></td>
                        <td>{{ $log->mekanik->name ?? 'N/A' }}</td>
                        <td>{{ $log->storeman->name ?? 'N/A' }}</td>
                        <td>
                            @if($log->waktu_kembali)
                                <span class="badge bg-label-success">Sudah Kembali</span>
                            @else
                                <span class="badge bg-label-danger">Belum Kembali</span>
                            @endif
                        </td>
                        <td>{{ $log->kondisi ? ucfirst($log->kondisi) : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada riwayat peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{-- Tampilkan link pagination --}}
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection