@extends('admin.layout.main')

@section('title', 'Manajemen Peralatan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Peralatan</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPeralatanModal">
                <i class="bx bx-plus me-1"></i> Tambah Peralatan
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Peralatan</th>
                        <th>Kode</th>
                        <th>Stok Total</th>
                        <th>Dipinjam</th>
                        <th>Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($peralatan as $item)
                    <tr>
                        <td><strong>{{ $item->nama }}</strong></td>
                        <td>{{ $item->kode }}</td>
                        <td><span class="badge bg-label-info">{{ $item->stok_total }}</span></td>
                        <td><span class="badge bg-label-warning">{{ $item->stok_dipinjam }}</span></td>
                        <td><span class="badge bg-label-success">{{ $item->stok_tersedia }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-peralatan" data-bs-toggle="modal"
                                        data-bs-target="#editPeralatanModal" data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama }}" data-kode="{{ $item->kode }}"
                                        data-stok_total="{{ $item->stok_total }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.peralatan.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data peralatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $peralatan->links() }}
        </div>
    </div>
</div>

{{-- Memanggil file modal (pastikan pathnya benar atau langsung letakkan di sini) --}}
@include('admin.peralatan.create')
@include('admin.peralatan.edit')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-peralatan').forEach(button => {
            button.addEventListener('click', function () {
                const modal = document.querySelector('#editPeralatanModal');
                const form = modal.querySelector('form');
                
                const baseUrl = "{{ url('admin/peralatan') }}"; // Sesuaikan dengan route Anda
                form.action = `${baseUrl}/${this.dataset.id}`;
                
                // Mengisi value pada setiap input di modal
                modal.querySelector('#edit-nama').value = this.dataset.nama;
                modal.querySelector('#edit-kode').value = this.dataset.kode;
                modal.querySelector('#edit-stok_total').value = this.dataset.stok_total;
            });
        });
    });
</script>
@endpush