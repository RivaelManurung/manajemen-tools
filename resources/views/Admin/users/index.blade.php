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
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($users as $user)
                    <tr>
                        <td><strong>{{ $user->fullname }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->jobTitle?->name }}</td>
                        <td>{{ $user->department?->name }}</td>
                        <td><span class="badge bg-label-primary">{{ ucfirst($user->peran) }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-user" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal" data-id="{{ $user->id }}"
                                        data-fullname="{{ $user->fullname }}" data-username="{{ $user->username }}"
                                        data-email="{{ $user->email }}" data-peran="{{ $user->peran }}"
                                        data-job_title_id="{{ $user->job_title_id }}"
                                        data-department_id="{{ $user->department_id }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin menghapus user ini?');">
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
                        <td colspan="7" class="text-center">Tidak ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- Memanggil modal --}}
@include('admin.users.create')
@include('admin.users.edit')
@endsection

{{-- Script dipindah ke bagian bawah --}}
@push('scripts')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-user');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Mengambil semua data dari atribut data-*
                const data = this.dataset;

                const modal = document.querySelector('#editUserModal');
                const form = modal.querySelector('form');
                
                // Set URL action untuk form update
                const baseUrl = "{{ url('users') }}"; // Sesuaikan dengan route Anda
                form.action = `${baseUrl}/${data.id}`;
                
                // Isi nilai ke setiap input di dalam modal
                modal.querySelector('#edit-fullname').value = data.fullname;
                modal.querySelector('#edit-username').value = data.username;
                modal.querySelector('#edit-email').value = data.email;
                modal.querySelector('#edit-peran').value = data.peran;
                // PERBAIKAN: Mengisi nilai untuk dropdown baru
                modal.querySelector('#edit-job_title_id').value = data.job_title_id;
                modal.querySelector('#edit-department_id').value = data.department_id;
            });
        });
    });
</script>
@endpush