<div class="modal fade" id="createPeralatanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Peralatan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.peralatan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Peralatan</label>
                        <input type="text" class="form-control" name="nama" placeholder="Contoh: Obeng Plus Besar" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Peralatan</label>
                        <input type="text" class="form-control" name="kode" placeholder="Contoh: OBG-001" required />
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