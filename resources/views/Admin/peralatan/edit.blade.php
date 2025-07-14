<div class="modal fade" id="editPeralatanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Peralatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Action form akan diisi oleh Javascript --}}
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Peralatan</label>
                        <input type="text" id="edit-nama" class="form-control" name="nama" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Peralatan</label>
                        <input type="text" id="edit-kode" class="form-control" name="kode" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="edit-status" name="status" class="form-select" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                        </select>
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