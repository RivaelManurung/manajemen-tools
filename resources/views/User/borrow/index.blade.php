@extends('admin.layout.main')

@section('title', 'Peminjaman & Pengembalian')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            {{-- Menampilkan notifikasi sukses atau error --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h4 class="text-center mb-4">Manajemen Tools</h4>

                    {{-- Navigasi Tabs --}}
                    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-borrow-tab" data-bs-toggle="pill" data-bs-target="#pills-borrow" type="button" role="tab">Peminjaman</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-return-tab" data-bs-toggle="pill" data-bs-target="#pills-return" type="button" role="tab">Pengembalian</button>
                        </li>
                    </ul>

                    {{-- Isi Konten Tab --}}
                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-borrow" role="tabpanel">
                            <form action="{{ route('admin.borrow.store') }}" method="POST">
                                @csrf

                                {{-- Kolom ini hanya tampil jika yang login adalah ADMIN --}}
                                @if (Auth::user()->peran === 'admin')
                                <div class="mb-3">
                                    <label class="form-label">Nama Storeman (Penanggung Jawab)</label>
                                    <select name="storeman_id" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Storeman --</option>
                                        @foreach($storemen as $storeman)
                                            <option value="{{ $storeman->id }}">{{ $storeman->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                               {{-- Cek apakah pengguna yang login adalah admin --}}
@if (Auth::user()->peran === 'admin')
    {{-- Jika ADMIN, tampilkan dropdown untuk memilih mekanik --}}
    <div class="mb-3">
        <label class="form-label">Nama Mekanik (Peminjam)</label>
        <select name="mechanic_id" class="form-select" required>
            <option value="" disabled selected>-- Pilih Mekanik --</option>
            @foreach($mechanics as $mechanic)
                <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
            @endforeach
        </select>
    </div>
@else
    {{-- Jika bukan admin (berarti MEKANIK), tampilkan namanya dan kirim ID-nya secara tersembunyi --}}
    <div class="mb-3">
        <label class="form-label">Nama Mekanik (Peminjam)</label>
        {{-- Tampilkan nama mekanik yang login, tapi field ini tidak bisa diubah --}}
        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
        {{-- Kirim ID mekanik yang login secara tersembunyi --}}
        <input type="hidden" name="mechanic_id" value="{{ Auth::id() }}">
    </div>
@endif
                                <div class="mb-3">
                                    <label class="form-label">Pilih Peralatan</label>
                                    <select name="tool_id" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Peralatan --</option>
                                        {{-- Loop ini hanya menampilkan peralatan yang statusnya 'tersedia' --}}
                                        @foreach($tools->where('status', 'tersedia') as $tool)
                                            <option value="{{ $tool->id }}">{{ $tool->nama }} ({{ $tool->kode }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary d-grid w-100">Simpan Peminjaman</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-return" role="tabpanel">
                            <form action="{{ route('admin.return.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Pilih Peralatan yang Dikembalikan</label>
                                    <select name="tool_id" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Peralatan --</option>
                                        {{-- Loop ini hanya menampilkan peralatan yang statusnya 'dipinjam' --}}
                                        @foreach($tools->where('status', 'dipinjam') as $tool)
                                            <option value="{{ $tool->id }}">{{ $tool->nama }} ({{ $tool->kode }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kondisi Barang</label>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check form-check-inline">
                                            <input class="btn-check" type="radio" name="condition" id="sangat-baik" value="sangat baik" required>
                                            <label class="btn btn-outline-secondary" for="sangat-baik">Sangat Baik</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="btn-check" type="radio" name="condition" id="baik" value="baik">
                                            <label class="btn btn-outline-secondary" for="baik">Baik</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="btn-check" type="radio" name="condition" id="rusak" value="rusak">
                                            <label class="btn btn-outline-secondary" for="rusak">Rusak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary d-grid w-100">Simpan Pengembalian</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection