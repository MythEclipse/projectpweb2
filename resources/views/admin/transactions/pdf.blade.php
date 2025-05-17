<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        /* Style sederhana untuk tabel PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>
    Laporan Transaksi {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}
    </h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Produk</th>
                <th>Ukuran / Warna</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Tanggal</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->order->user->name ?? '-' }}</td>
                    <td>{{ $trx->product->name ?? '-' }}</td>
                    <td>{{ $trx->size->name ?? '-' }} / {{ $trx->color->name ?? '-' }}</td>
                    <td>{{ $trx->quantity }}</td>
                    <td>Rp{{ number_format($trx->price, 0, ',', '.') }}</td>
                    <td>{{ $trx->created_at->format('d-m-Y') }}</td>
                    <td>{{ $trx->order->payment_status ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
