<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .period { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi Material</h1>
    <div class="period">
        Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Project</th>
                <th>Material</th>
                <th>Kuantitas</th>
                <th>User</th>
                <th>Vendor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>{{ $transaction->project->name }}</td>
                    <td>{{ $item->material->name }}</td>
                    <td>{{ $item->quantity }} {{ $item->material->unit }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ $transaction->vendor->name ?? '-' }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
