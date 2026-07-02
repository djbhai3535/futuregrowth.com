<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Withdrawals Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #fff;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #111;
        }
        .metadata {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        @media print {
            body { margin: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FutureGrowth.tech - Withdrawals Payout Report</h1>
        <div class="metadata">
            Generated on: {{ date('Y-m-d H:i:s') }}
            @if($fromDate || $toDate)
                | Filter: {{ $fromDate ?? 'Beginning' }} to {{ $toDate ?? 'Now' }}
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User / Account</th>
                <th>Destination Wallet Address</th>
                <th>Balance Source</th>
                <th class="text-right">Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($data as $row)
                @php if ($row->status === 'approved') $total += $row->amount; @endphp
                <tr>
                    <td>#{{ $row->id }}</td>
                    <td>
                        {{ $row->user ? $row->user->name : 'N/A' }}
                        @if($row->user)
                            <small>({{ $row->user->username }})</small>
                        @endif
                    </td>
                    <td><code>{{ $row->wallet_address }}</code></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $row->wallet_type)) }}</td>
                    <td class="text-right">${{ number_format($row->amount, 2) }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ $row->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="4" class="text-right">Total Approved Withdrawals:</td>
                <td class="text-right">${{ number_format($total, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
