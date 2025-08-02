@extends('admin.layout.main')

@section('title', 'Manajemen Storeman')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Storeman</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStoremanModal">
                <i class="bx bx-plus me-1"></i> Tambah Storeman
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Storeman</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($storemen as $storeman)
                    <tr>
                        <td><strong>{{ $storeman->nama }}</strong></td>
                        <td>{{ $storeman->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-storeman" data-bs-toggle="modal"
                                        data-bs-target="#editStoremanModal" data-id="{{ $storeman->id }}"
                                        data-nama="{{ $storeman->nama }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.storeman.destroy', $storeman->id) }}" method="POST"
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
                        <td colspan="3" class="text-center">Tidak ada data storeman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $storemen->links() }}
        </div>
    </div>
</div>

{{-- Memanggil file modal --}}
@include('admin.storeman.create')
@include('admin.storeman.edit')
@endsection

@push('scripts')
<script>
    // Script untuk mengisi data ke modal edit
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-storeman').forEach(button => {
            button.addEventListener('click', function () {
                const modal = document.querySelector('#editStoremanModal');
                const form = modal.querySelector('form');
                
                const baseUrl = "{{ url('storeman') }}";
                form.action = `${baseUrl}/${this.dataset.id}`;
                
                modal.querySelector('#edit-nama').value = this.dataset.nama;
            });
        });
    });
</script>
@endpush