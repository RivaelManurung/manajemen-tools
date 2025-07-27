@extends('admin.layout.main')

@section('title', 'Manajemen Departemen')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Departemen</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                <i class="bx bx-plus me-1"></i> Tambah Departemen
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Departemen</th>
                        <th>Jumlah Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($departments as $department)
                    <tr>
                        <td><strong>{{ $department->name }}</strong></td>
                        <td><span class="badge bg-label-info">{{ $department->users_count }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-department" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDepartmentModal" 
                                        data-id="{{ $department->id }}"
                                        data-name="{{ $department->name }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
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
                        <td colspan="3" class="text-center">Tidak ada data departemen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $departments->links() }}
        </div>
    </div>
</div>

{{-- Memanggil file modal --}}
@include('admin.departemen.create')
@include('admin.departemen.edit')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-department').forEach(button => {
            button.addEventListener('click', function () {
                const modal = document.querySelector('#editDepartmentModal');
                const form = modal.querySelector('form');
                
                const baseUrl = "{{ url('departments') }}";
                form.action = `${baseUrl}/${this.dataset.id}`;
                
                modal.querySelector('#edit-name').value = this.dataset.name;
            });
        });
    });
</script>
@endpush