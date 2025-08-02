@extends('admin.layout.main')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Pengguna</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal"><i class="bx bx-plus me-1"></i> Tambah Pengguna</button>
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
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-user" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="{{ $user->id }}" data-fullname="{{ $user->fullname }}" data-username="{{ $user->username }}" data-email="{{ $user->email }}" data-peran="{{ $user->peran }}" data-job_title_id="{{ $user->job_title_id }}" data-department_id="{{ $user->department_id }}"><i class="bx bx-edit-alt me-1"></i> Edit</button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-1"></i> Hapus</button>
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
        <div class="mt-3 px-3">{{ $users->links() }}</div>
    </div>
</div>
@include('admin.users.create')
@include('admin.users.edit')
@endsection

@push('scripts')
<script>
// Menjalankan script setelah seluruh dokumen siap
$(function() {
    // Fungsi ini HANYA untuk mengisi data ke dalam modal saat tombol edit diklik.
    // Fungsi toggle password sudah tidak ada karena ditangani oleh script template.
    $('body').on('click', '.edit-user', function() {
        const data = $(this).data();
        const modal = $('#editUserModal');
        const form = modal.find('form');
        const baseUrl = "{{ route('admin.users.index') }}";
        form.attr('action', `${baseUrl}/${data.id}`);

        // Isi semua field
        modal.find('#edit-fullname').val(data.fullname);
        modal.find('#edit-username').val(data.username);
        modal.find('#edit-email').val(data.email);
        modal.find('#edit-peran').val(data.peran);
        modal.find('#edit-job_title_id').val(data.job_title_id);
        modal.find('#edit-department_id').val(data.department_id);

        // Kosongkan field password setiap kali modal dibuka
        modal.find('#edit-password').val('');
        modal.find('#edit-password-confirmation').val('');
    });
});
</script>
@endpush