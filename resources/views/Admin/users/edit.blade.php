{{-- Modal untuk Edit Data Pengguna --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    {{-- Field lain tidak berubah --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="edit-fullname" class="form-control" name="fullname" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" id="edit-username" class="form-control" name="username" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="edit-email" class="form-control" name="email" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan (Job Title)</label>
                        <select id="edit-job_title_id" name="job_title_id" class="form-select" required>
                            @foreach($jobTitles as $job)
                            <option value="{{ $job->id }}">{{ $job->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Departemen</label>
                        <select id="edit-department_id" name="department_id" class="form-select" required>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peran Sistem</label>
                        <select id="edit-peran" name="peran" class="form-select" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    {{-- =================== STRUKTUR BAWAAN TEMPLATE =================== --}}
                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="edit-password">Password Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" id="edit-password" class="form-control" name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="edit-password-addon" />
                            <span id="edit-password-addon" class="input-group-text cursor-pointer">
                                <i class="icon-base bx bx-hide"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="edit-password-confirmation">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" id="edit-password-confirmation" class="form-control"
                                name="password_confirmation"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="edit-password-confirmation-addon" />
                            <span id="edit-password-confirmation-addon" class="input-group-text cursor-pointer">
                                <i class="icon-base bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                    {{-- ============================================================== --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>