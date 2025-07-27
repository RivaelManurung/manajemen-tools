<div class="modal fade" id="createStoremanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Storeman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.storeman.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Storeman</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>