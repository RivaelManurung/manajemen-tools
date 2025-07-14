<div class="tab-pane fade show active" id="pills-borrow" role="tabpanel">
    <h5 class="mb-3">Form Peminjaman Peralatan</h5>
    <form action="{{ route('admin.borrow.store') }}" method="POST">
        @csrf

        {{-- KOLOM INI HANYA TAMPIL JIKA YANG LOGIN ADALAH ADMIN --}}
        @if (Auth::user()->peran === 'admin')
        <div class="mb-3">
            <label class="form-label">Nama Storeman (Penanggung Jawab)</label>
            <select name="storeman_id" class="form-select" required>
                <option value="" disabled selected>-- Pilih Storeman --</option>
                {{-- Variabel $storemen hanya ada untuk admin --}}
                @foreach($storemen as $storeman)
                    <option value="{{ $storeman->id }}">{{ $storeman->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Nama Mekanik (Peminjam)</label>
            <select name="mechanic_id" class="form-select" required>
                <option value="" disabled selected>-- Pilih Mekanik --</option>
                @foreach($mechanics as $mechanic)
                    <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Pilih Peralatan (Hanya yang Tersedia)</label>
            <select name="tool_id" class="form-select" required>
                <option value="" disabled selected>-- Pilih Peralatan --</option>
                @foreach($tools->where('status', 'tersedia') as $tool)
                    <option value="{{ $tool->id }}">{{ $tool->nama }} ({{ $tool->kode }})</option>
                @endforeach
            </select>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Pinjamkan Alat</button>
        </div>
    </form>
</div>
