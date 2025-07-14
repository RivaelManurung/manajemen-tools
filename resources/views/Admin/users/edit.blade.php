<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Action form akan diisi oleh Javascript --}}
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="edit-name" class="form-control" name="name" required />
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
                        <label class="form-label">Peran</label>
                        <select id="edit-peran" name="peran" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="storeman">Storeman</option>
                            <option value="mekanik">Mekanik</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" class="form-control" name="password" placeholder="Isi jika ingin ganti password" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="password_confirmation" />
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
            </form>
        </div>
    </div>
</div>