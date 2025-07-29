<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
        .details-list { list-style-type: none; padding-left: 0; margin: 0; }
    </style>
</head>
<body>
    <h2>Laporan Transaksi Peminjaman</h2>
    <p>Tanggal Cetak: {{ $tanggalCetak }}</p>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>User</th>
                <th>Storeman</th>
                <th>Detail Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksis as $transaksi)
            <tr>
                <td>{{ $transaksi->kode_transaksi }}</td>
                <td>{{ $transaksi->tipe }}</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}</td>
                <td>{{ $transaksi->user?->fullname }}</td>
                <td>{{ $transaksi->storeman?->nama ?? '-' }}</td>
                <td>
                    <ul class="details-list">
                    @foreach($transaksi->details as $detail)
                        <li>
                            {{ $detail->peralatan?->nama }} ({{ $detail->jumlah }})
                            @if($detail->kondisi)
                            - {{ $detail->kondisi }}
                            @endif
                        </li>
                    @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>