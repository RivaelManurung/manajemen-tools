<div class="modal fade" id="createPeralatanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Peralatan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('peralatan.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Peralatan</label>
                        <input type="text" class="form-control" name="nama" placeholder="Contoh: Obeng Plus Besar" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Peralatan</label>
                        <input type="text" class="form-control" name="kode" placeholder="Contoh: OBG-001" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Total</label>
                        <input type="number" class="form-control" name="stok_total" placeholder="Contoh: 10" required min="0" />
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