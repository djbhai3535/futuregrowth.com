<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users Directory Report</title>
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
            font-size: 11px;
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
        <h1>FutureGrowth.tech - Users Directory Report</h1>
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
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Referral Code</th>
                <th>Status</th>
                <th>Deposit Bal</th>
                <th>ROI Bal</th>
                <th>Referral Bal</th>
                <th>Bonus Bal</th>
                <th>Joined Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>#{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->username }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->phone }}</td>
                    <td>{{ $row->referral_code }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td class="text-right">${{ number_format($row->wallet ? $row->wallet->deposit_balance : 0, 2) }}</td>
                    <td class="text-right">${{ number_format($row->wallet ? $row->wallet->roi_balance : 0, 2) }}</td>
                    <td class="text-right">${{ number_format($row->wallet ? $row->wallet->referral_balance : 0, 2) }}</td>
                    <td class="text-right">${{ number_format($row->wallet ? $row->wallet->bonus_balance : 0, 2) }}</td>
                    <td>{{ $row->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
