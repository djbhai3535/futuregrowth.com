<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #0f172a; color: #f8fafc; padding: 20px; }
        .card { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 20px; max-width: 600px; margin: 0 auto; }
        .header { font-size: 20px; font-weight: bold; color: #3b82f6; margin-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 10px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .label { color: #94a3b8; }
        .value { color: #ffffff; font-weight: bold; }
        .btn { display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">New Withdrawal Request Submitted</div>
        <div class="detail-row">
            <span class="label">User:</span>
            <span class="value">{{ $withdrawal->user->name }} ({{ $withdrawal->user->username }})</span>
        </div>
        <div class="detail-row">
            <span class="label">Amount:</span>
            <span class="value">${{ number_format($withdrawal->amount, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Wallet:</span>
            <span class="value">{{ str_replace('_', ' ', \Illuminate\Support\Str::title($withdrawal->wallet_type)) }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Wallet Address:</span>
            <span class="value">{{ $withdrawal->wallet_address }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Submission Date:</span>
            <span class="value">{{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</span>
        </div>
        <a href="{{ route('admin.withdrawals') }}" class="btn">Go to Withdrawal Management</a>
    </div>
</body>
</html>
