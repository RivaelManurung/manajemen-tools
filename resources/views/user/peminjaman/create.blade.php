@extends('admin.layout.main')

@section('title', 'Form Peminjaman Peralatan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Peminjaman Peralatan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.peminjaman.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Peminjam</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->fullname }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="storeman_id" class="form-label">Petugas (Storeman)</label>
                                <select name="storeman_id" id="storeman_id" class="form-select" required>
                                    <option value="">-- Pilih Petugas --</option>
                                    @foreach ($daftarStoreman as $storeman)
                                    <option value="{{ $storeman->id }}">{{ $storeman->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>

                        <h6>Detail Peralatan</h6>
                        <div class="item-list">
                            <div class="row item-row mb-2">
                                <div class="col-md-7">
                                    <select name="items[0][peralatan_id]" class="form-select" required>
                                        <option value="">-- Pilih Peralatan --</option>
                                        @foreach ($daftarPeralatan as $peralatan)
                                        <option value="{{ $peralatan->id }}">{{ $peralatan->nama }} (Sisa: {{
                                            $peralatan->stok_tersedia }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[0][jumlah]" class="form-control"
                                        placeholder="Jumlah" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-item-btn"
                                        style="display: none;">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-secondary mt-2 add-item-btn">Tambah Item</button>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary d-grid w-100">Ajukan Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk menambah dan menghapus baris item di kedua tab
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.add-item-btn').forEach(button => {
            let itemIndex = 1;
            button.addEventListener('click', function() {
                const itemList = this.previousElementSibling;
                const firstRow = itemList.querySelector('.item-row');
                const newRow = firstRow.cloneNode(true);

                newRow.querySelector('select').name = `items[${itemIndex}][peralatan_id]`;
                newRow.querySelector('input').name = `items[${itemIndex}][jumlah]`;
                newRow.querySelector('select').value = '';
                newRow.querySelector('input').value = '';

                const removeBtn = newRow.querySelector('.remove-item-btn');
                removeBtn.style.display = 'inline-block';
                removeBtn.addEventListener('click', function () { newRow.remove(); });
                
                itemList.appendChild(newRow);
                itemIndex++;
            });
        });
    });
</script>
@endpush