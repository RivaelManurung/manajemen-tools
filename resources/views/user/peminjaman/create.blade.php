@extends('user.layout.main')

@section('title', 'Manajemen Tools')

@push('styles')
<style>
    /* Memberi latar belakang gradien ke seluruh halaman */
    body {
        background: linear-gradient(125deg, #e0eafc, #d3e1f7);
    }
    .tool-management-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 2rem 1rem;
    }
    .tool-management-card {
        background-color: #ffffff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 15px 45px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 700px; 
    }
    .card-title {
        text-align: center;
        font-weight: 600;
        font-size: 1.5rem;
        margin-bottom: 25px;
    }
    .nav-pills-container {
        background-color: #f0f2f5;
        border-radius: 12px;
        padding: 5px;
        display: inline-flex;
        margin-bottom: 25px;
    }
    .nav-pills .nav-link {
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }
    .nav-pills .nav-link:not(.active) {
        background: transparent;
        color: #6c757d;
    }
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    .form-label {
        font-weight: 500;
        color: #555;
    }
    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 12px;
    }
    .add-tool-btn {
        color: #0d6efd;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        margin-top: 15px;
    }
    .btn-save {
        background-color: #e9ecef;
        border: none;
        color: #6c757d;
        font-weight: 600;
        padding: 14px;
        border-radius: 12px;
        width: 100%;
        margin-top: 25px;
        transition: all 0.2s ease-in-out;
    }
    .btn-save.active {
        background-color: #0d6efd;
        color: white;
        cursor: pointer;
    }
    .tool-list-item {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.75rem 1rem;
        background-color: #f0f2f5;
        border-radius: 0.5rem;
    }
    .item-details {
        flex-grow: 1;
        margin-bottom: 5px;
    }
    .item-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .quantity-input {
        background-color: #ffffff !important;
        color: #000000 !important;
        border-radius: 0.5rem;
        text-align: center;
        border-color: #ced4da;
    }
    .remove-item-btn {
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        line-height: 1;
        border: none;
        padding-bottom: 2px;
    }
    .kondisi-select {
        transition: background-color 0.3s ease;
    }
    .kondisi-sangat-baik {
        background-color: #d1e7dd !important;
        border-color: #a3cfbb !important;
    }
    .kondisi-baik {
        background-color: #fff3cd !important;
        border-color: #ffe69c !important;
    }
    .kondisi-rusak {
        background-color: #f8d7da !important;
        border-color: #f1aeb5 !important;
    }
</style>
@endpush

@section('content')
<div class="tool-management-container">
    <div class="tool-management-card">
        <h4 class="card-title">Manajemen Tools</h4>

        {{-- Navigasi Tab --}}
        <div class="d-flex justify-content-center">
            <div class="nav-pills-container">
                <ul class="nav nav-pills" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#peminjaman-pane" type="button">Peminjaman</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pengembalian-pane" type="button">Pengembalian</button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if(session('success')) <div class="alert alert-success mt-3">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger mt-3">{{ session('error') }}</div> @endif

        <div class="tab-content mt-4">
            {{-- ====================== PANEL PEMINJAMAN ====================== --}}
            <div class="tab-pane fade show active" id="peminjaman-pane" role="tabpanel">
                <form id="peminjamanForm" action="{{ route('user.peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Mekanik</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->fullname }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Storeman</label>
                        <select class="form-select" name="storeman_id" required>
                            <option value="">Pilih Storeman</option>
                            @foreach($daftarStoreman as $storeman)
                                <option value="{{ $storeman->id }}">{{ $storeman->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="peminjaman-item-list" class="mt-4"></div>
                    <a href="#" class="add-tool-btn" data-bs-toggle="modal" data-bs-target="#peminjamanToolModal"><i class="bx bx-plus"></i> Tambah Tools</a>
                    
                    {{-- Tombol Simpan dipindah ke bawah setelah tombol Tambah Tools --}}
                    <button type="submit" class="btn btn-save">Simpan Peminjaman</button>
                </form>
            </div>

            {{-- ====================== PANEL PENGEMBALIAN ====================== --}}
            <div class="tab-pane fade" id="pengembalian-pane" role="tabpanel">
                <form id="pengembalianForm" action="{{ route('user.kembalikan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Mekanik</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->fullname }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Storeman</label>
                        <select class="form-select" name="storeman_id" required>
                            <option value="">Pilih Storeman</option>
                            @foreach($daftarStoreman as $storeman)
                                <option value="{{ $storeman->id }}">{{ $storeman->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="pengembalian-item-list" class="mt-4"></div>
                    <a href="#" class="add-tool-btn" data-bs-toggle="modal" data-bs-target="#pengembalianToolModal"><i class="bx bx-plus"></i> Tambah Tools untuk Dikembalikan</a>
                    
                    {{-- Tombol Simpan dipindah ke bawah setelah tombol Tambah Tools --}}
                    <button type="submit" class="btn btn-save">Simpan Pengembalian</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ====================== MODAL ====================== --}}
<div class="modal fade" id="peminjamanToolModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Pilih Tools</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3 tool-search-input" placeholder="🔍 Cari nama tool...">
                <div class="modal-tool-list list-group list-group-flush"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pengembalianToolModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Pilih Tools yang Akan Dikembalikan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3 tool-search-input" placeholder="🔍 Cari nama tool...">
                <div class="modal-tool-list list-group list-group-flush"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dataSources = {
        peminjaman: @json($peralatanTersedia),
        pengembalian: @json($peralatanDipinjam)
    };

    const setupForm = (config) => {
        const form = document.getElementById(config.formId);
        if (!form) return;
        
        const saveButton = form.querySelector('.btn-save');
        const listContainer = document.getElementById(config.listContainerId);
        const modalEl = document.getElementById(config.modalId);
        if (!modalEl) return; 

        const modal = new bootstrap.Modal(modalEl);
        const modalSearchInput = modalEl.querySelector('.tool-search-input');
        const modalListContainer = modalEl.querySelector('.modal-tool-list');
        let itemIndex = 0;

        const checkFormValidity = () => {
            const requiredInputs = form.querySelectorAll('[required]');
            let allValid = listContainer.children.length > 0;
            requiredInputs.forEach(input => {
                if (!input.value) allValid = false;
            });
            saveButton.classList.toggle('active', allValid);
        };

        const renderModalList = (data, query = '') => {
            modalListContainer.innerHTML = '';
            const filteredData = data.filter(d => {
                const name = d.nama_peralatan || d.nama;
                return name.toLowerCase().includes(query.toLowerCase());
            });
            
            if (filteredData.length === 0) {
                modalListContainer.innerHTML = '<p class="text-center text-muted p-3">Tool tidak ditemukan.</p>';
                return;
            }

            filteredData.forEach(tool => {
                const id = tool.peralatan_id || tool.id;
                const name = tool.nama_peralatan || tool.nama;
                const stock = tool.jumlah_dipinjam || tool.stok_tersedia;
                const isAdded = form.querySelector(`input[name$="[peralatan_id]"][value="${id}"]`);
                const btnText = isAdded ? 'Ditambahkan' : 'Pilih';

                modalListContainer.insertAdjacentHTML('beforeend', `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">${name}</h6>
                            <small class="text-muted">${config.stockLabel}: ${stock}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary select-tool-btn" 
                                data-id="${id}" data-name="${name}" data-stock="${stock}" ${isAdded ? 'disabled' : ''}>
                            ${btnText}
                        </button>
                    </div>`);
            });
        };

        const addToolToForm = (id, name, maxStock) => {
            const currentItemIndex = itemIndex++;
            
            let conditionHtml = '';
            if (config.formId === 'pengembalianForm') {
                conditionHtml = `
                    <select name="items[${currentItemIndex}][kondisi]" class="form-select form-select-sm kondisi-select kondisi-sangat-baik" required style="width: 130px;">
                        <option value="sangat baik">Sangat Baik</option>
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                `;
            }

            listContainer.insertAdjacentHTML('beforeend', `
                <div class="tool-list-item" data-id="${id}">
                    <div class="item-details">
                        <span class="fw-medium">${name}</span>
                    </div>
                    <div class="item-actions">
                        ${conditionHtml}
                        <input type="number" name="items[${currentItemIndex}][jumlah]" class="form-control form-control-sm quantity-input" value="1" min="1" max="${maxStock}" required style="width: 70px;">
                        <input type="hidden" name="items[${currentItemIndex}][peralatan_id]" value="${id}">
                        <button type="button" class="btn remove-item-btn p-0">&times;</button>
                    </div>
                </div>`);
            
            checkFormValidity();
        };

        modalEl.addEventListener('show.bs.modal', () => {
             renderModalList(config.dataSource, modalSearchInput.value);
        });

        modalSearchInput.addEventListener('input', (e) => {
            renderModalList(config.dataSource, e.target.value);
        });

        modalListContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('select-tool-btn')) {
                const { id, name, stock } = e.target.dataset;
                addToolToForm(id, name, stock);
                modal.hide();
            }
        });

        listContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item-btn')) {
                e.target.closest('[data-id]').remove();
                checkFormValidity();
            }
        });
        
        listContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('kondisi-select')) {
                const selectEl = e.target;
                selectEl.classList.remove('kondisi-sangat-baik', 'kondisi-baik', 'kondisi-rusak');
                if (selectEl.value === 'sangat baik') selectEl.classList.add('kondisi-sangat-baik');
                else if (selectEl.value === 'baik') selectEl.classList.add('kondisi-baik');
                else if (selectEl.value === 'rusak') selectEl.classList.add('kondisi-rusak');
            }
        });

        form.addEventListener('input', checkFormValidity);
        checkFormValidity();
    };

    // INISIALISASI KEDUA FORM
    setupForm({
        formId: 'peminjamanForm',
        listContainerId: 'peminjaman-item-list',
        modalId: 'peminjamanToolModal',
        dataSource: dataSources.peminjaman,
        stockLabel: 'Tersedia'
    });

    setupForm({
        formId: 'pengembalianForm',
        listContainerId: 'pengembalian-item-list',
        modalId: 'pengembalianToolModal',
        dataSource: dataSources.pengembalian,
        stockLabel: 'Dipinjam'
    });
});
</script>
@endpush