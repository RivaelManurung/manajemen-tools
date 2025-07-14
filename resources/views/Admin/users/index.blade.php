@extends('admin.layout.main')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Pengguna</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bx bx-plus me-1"></i> Tambah Pengguna
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-label-primary">{{ ucfirst($user->peran) }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-user" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editUserModal" 
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}" 
                                        data-username="{{ $user->username }}"
                                        data-email="{{ $user->email }}" 
                                        data-peran="{{ $user->peran }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus user ini?');">
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
                        <td colspan="5" class="text-center">Tidak ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Panggil modal dari file terpisah --}}
@include('admin.users.create')
@include('admin.users.edit')
@endsection

{{-- Gunakan @push untuk mengirim script ke layout utama --}}
@push('scripts')
<script>
    // Pastikan script dieksekusi setelah semua HTML dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Cari semua tombol dengan class .edit-user
        const editButtons = document.querySelectorAll('.edit-user');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Ambil data dari atribut data-* tombol yang diklik
                const userId = this.dataset.id;
                const userName = this.dataset.name;
                const userUsername = this.dataset.username;
                const userEmail = this.dataset.email;
                const userPeran = this.dataset.peran;

                // Cari modal edit
                const modal = document.querySelector('#editUserModal');
                
                // Cari form di dalam modal
                const form = modal.querySelector('form');
                
                // Set URL action untuk form update
                const baseUrl = "{{ url('admin/users') }}";
                form.action = `${baseUrl}/${userId}`;
                
                // Isi nilai ke setiap input di dalam modal
                modal.querySelector('#edit-name').value = userName;
                modal.querySelector('#edit-username').value = userUsername;
                modal.querySelector('#edit-email').value = userEmail;
                modal.querySelector('#edit-peran').value = userPeran;
            });
        });
    });
</script>
@endpush