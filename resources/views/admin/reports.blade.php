@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports & Export Command</h2>
        <p class="text-muted small">Generate and download standard reports in CSV, Microsoft Excel, or printable PDF formats.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6" data-aos="fade-up">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-4 text-warning border-bottom border-secondary pb-2"><i class="bi bi-gear-fill me-2"></i> Configure Export parameters</h5>
            
            <form action="{{ route('admin.reports.export') }}" method="GET" target="_blank">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">1. Select Report Category</label>
                    <select name="type" class="form-select bg-dark border-secondary text-white p-3" required>
                        <option value="users">Registered Users Directory (Full Listing & Balances)</option>
                        <option value="deposits">Transaction Deposits Report (Amounts, TXIDs, & Statuses)</option>
                        <option value="withdrawals">Transaction Withdrawals Report (Payouts, Wallets & Statuses)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">2. Choose File Format</label>
                    <select name="format" class="form-select bg-dark border-secondary text-white p-3" required>
                        <option value="csv">Standard CSV Text (.csv)</option>
                        <option value="excel">Microsoft Excel Sheet (.xls)</option>
                        <option value="pdf">Printable Document / PDF (.pdf)</option>
                    </select>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Start Date (Optional)</label>
                        <input type="date" name="from_date" class="form-control bg-dark border-secondary text-white p-3">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">End Date (Optional)</label>
                        <input type="date" name="to_date" class="form-control bg-dark border-secondary text-white p-3">
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 py-3 fw-bold fs-6"><i class="bi bi-cloud-arrow-down-fill me-2"></i> Generate & Download Report</button>
            </form>
        </div>
    </div>
</div>
@endsection
