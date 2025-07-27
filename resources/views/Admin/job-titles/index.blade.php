@extends('admin.layout.main')

@section('title', 'Manajemen Job Title')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manajemen Job Title</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJobTitleModal">
                <i class="bx bx-plus me-1"></i> Tambah Job Title
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Jabatan</th>
                        <th>Jumlah Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($jobTitles as $jobTitle)
                    <tr>
                        <td><strong>{{ $jobTitle->name }}</strong></td>
                        <td><span class="badge bg-label-info">{{ $jobTitle->users_count }}</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-job-title" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#editJobTitleModal" 
                                        data-id="{{ $jobTitle->id }}"
                                        data-name="{{ $jobTitle->name }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.job-titles.destroy', $jobTitle->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
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
                        <td colspan="3" class="text-center">Tidak ada data job title.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $jobTitles->links() }}
        </div>
    </div>
</div>

{{-- Memanggil file modal --}}
@include('admin.job-titles.create')
@include('admin.job-titles.edit')
@endsection

@push('scripts')
{{-- JavaScript untuk mengisi modal edit --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-job-title').forEach(button => {
            button.addEventListener('click', function () {
                const modal = document.querySelector('#editJobTitleModal');
                const form = modal.querySelector('form');
                
                // Membuat URL action form secara dinamis
                const baseUrl = "{{ url('job-titles') }}";
                form.action = `${baseUrl}/${this.dataset.id}`;
                
                // Mengisi value pada input nama
                modal.querySelector('#edit-name').value = this.dataset.name;
            });
        });
    });
</script>
@endpush