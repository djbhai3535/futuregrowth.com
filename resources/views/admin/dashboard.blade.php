@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-shield-check me-2"></i> Command Center</h2>
        <p class="text-muted small">Platform overview and real-time statistics.</p>
    </div>
    <div>
        <button class="btn btn-outline-light btn-sm me-2"><i class="bi bi-cloud-download"></i> Export Data</button>
        <button class="btn btn-warning btn-sm fw-bold text-dark"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Row 1 -->
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-4 position-relative border-info border-opacity-25 neon-glow-primary overflow-hidden">
            <i class="bi bi-people position-absolute top-0 end-0 m-3 display-4 text-info opacity-25"></i>
            <p class="text-info text-uppercase fw-bold small mb-1">Total Users</p>
            <h2 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $usersCount }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-4 position-relative border-primary border-opacity-25 overflow-hidden">
            <i class="bi bi-person-plus position-absolute top-0 end-0 m-3 display-4 text-primary opacity-25"></i>
            <p class="text-primary text-uppercase fw-bold small mb-1">Today's Users</p>
            <h2 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $todayUsersCount }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-4 position-relative border-success border-opacity-25 neon-glow-success overflow-hidden">
            <i class="bi bi-person-check position-absolute top-0 end-0 m-3 display-4 text-success opacity-25"></i>
            <p class="text-success text-uppercase fw-bold small mb-1">Active Users</p>
            <h2 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $activeUsersCount }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="400">
        <div class="glass-card p-4 position-relative border-danger border-opacity-25 overflow-hidden">
            <i class="bi bi-person-x position-absolute top-0 end-0 m-3 display-4 text-danger opacity-25"></i>
            <p class="text-danger text-uppercase fw-bold small mb-1">Suspended Users</p>
            <h2 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $suspendedUsersCount }}">0</span></h2>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Row 2 -->
    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-4 position-relative border-warning border-opacity-25 overflow-hidden">
            <i class="bi bi-arrow-down-circle position-absolute top-0 end-0 m-3 display-4 text-warning opacity-25"></i>
            <p class="text-warning text-uppercase fw-bold small mb-1">Pending Deposits</p>
            <h2 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $depositsPending }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-4 position-relative border-danger border-opacity-25 overflow-hidden">
            <i class="bi bi-arrow-up-circle position-absolute top-0 end-0 m-3 display-4 text-danger opacity-25"></i>
            <p class="text-danger text-uppercase fw-bold small mb-1">Pending Withdrawals</p>
            <h2 class="text-white fw-bold mb-0"><span class="countup" data-val="{{ $withdrawalsPending }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-4 position-relative border-success border-opacity-25 overflow-hidden">
            <i class="bi bi-play-circle position-absolute top-0 end-0 m-3 display-4 text-success opacity-25"></i>
            <p class="text-success text-uppercase fw-bold small mb-1">Running Investments</p>
            <h2 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $runningInvestments }}">0</span></h2>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Row 3 -->
    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card p-4 position-relative border-success border-opacity-25 overflow-hidden">
            <i class="bi bi-wallet2 position-absolute top-0 end-0 m-3 display-4 text-success opacity-25"></i>
            <p class="text-success text-uppercase fw-bold small mb-1">Total Deposits</p>
            <h2 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalDeposits }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card p-4 position-relative border-danger border-opacity-25 overflow-hidden">
            <i class="bi bi-cash-stack position-absolute top-0 end-0 m-3 display-4 text-danger opacity-25"></i>
            <p class="text-danger text-uppercase fw-bold small mb-1">Total Withdrawals</p>
            <h2 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalWithdrawals }}">0</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="300">
        <div class="glass-card p-4 position-relative border-info border-opacity-25 overflow-hidden">
            <i class="bi bi-safe position-absolute top-0 end-0 m-3 display-4 text-info opacity-25"></i>
            <p class="text-info text-uppercase fw-bold small mb-1">Total Investments</p>
            <h2 class="text-white fw-bold mb-0">$<span class="countup" data-val="{{ $totalInvestments }}">0</span></h2>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart Section -->
    <div class="col-lg-8" data-aos="fade-right">
        <div class="glass-card p-4 h-100 border-warning border-opacity-25 tech-bg-container">
            <!-- Subtle Tech Background Effects -->
            <div class="tech-grid-overlay"></div>
            <div class="tech-data-stream" style="background: linear-gradient(to bottom, transparent, rgba(234, 179, 8, 0.8), transparent);"></div>
            <div class="tech-data-stream tech-data-stream-2" style="background: linear-gradient(to bottom, transparent, rgba(234, 179, 8, 0.8), transparent);"></div>
            <div class="tech-data-stream tech-data-stream-3" style="background: linear-gradient(to bottom, transparent, rgba(234, 179, 8, 0.8), transparent);"></div>

            <div class="d-flex justify-content-between mb-4 position-relative">
                <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill text-warning me-2"></i> Financial Growth Matrix</h5>
                <span class="badge bg-dark text-muted border border-secondary px-3 py-1">Last 7 Days</span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4" data-aos="fade-left">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-lightning-charge-fill text-warning me-2"></i> Quick Actions</h5>
            
            <a href="{{ route('admin.users') }}" class="btn btn-outline-info w-100 py-3 mb-3 text-start d-flex justify-content-between align-items-center custom-hover">
                <div><i class="bi bi-people fs-4 d-block mb-1"></i> <span class="fw-bold">Manage Users</span></div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
            
            <a href="{{ route('admin.plans') }}" class="btn btn-outline-success w-100 py-3 mb-3 text-start d-flex justify-content-between align-items-center custom-hover">
                <div><i class="bi bi-box fs-4 d-block mb-1"></i> <span class="fw-bold">Investment Plans</span></div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
            
            <a href="{{ route('admin.tickets') }}" class="btn btn-outline-primary w-100 py-3 mb-3 text-start d-flex justify-content-between align-items-center custom-hover">
                <div><i class="bi bi-headset fs-4 d-block mb-1"></i> <span class="fw-bold">Support Tickets</span></div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
            
            <a href="{{ route('admin.faqs') }}" class="btn btn-outline-info w-100 py-3 mb-3 text-start d-flex justify-content-between align-items-center custom-hover">
                <div><i class="bi bi-question-circle fs-4 d-block mb-1"></i> <span class="fw-bold">Manage FAQs</span></div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('admin.documents') }}" class="btn btn-outline-light w-100 py-3 mb-3 text-start d-flex justify-content-between align-items-center custom-hover">
                <div><i class="bi bi-file-earmark-pdf fs-4 d-block mb-1"></i> <span class="fw-bold">Document Center</span></div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>

            <a href="{{ route('admin.settings') }}" class="btn btn-outline-warning w-100 py-3 text-start d-flex justify-content-between align-items-center custom-hover">
                <div><i class="bi bi-gear fs-4 d-block mb-1"></i> <span class="fw-bold">Platform Settings</span></div>
                <i class="bi bi-chevron-right text-muted"></i>
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Recent Registrations -->
    <div class="col-lg-6" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-3 text-warning"><i class="bi bi-person-plus-fill me-2"></i> Recent Registrations</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRegistrations as $reg)
                            <tr>
                                <td>
                                    <div class="fw-bold text-white">{{ $reg->name }}</div>
                                    <small class="text-muted">({{ $reg->username }})</small>
                                </td>
                                <td>{{ $reg->email }}</td>
                                <td>{{ $reg->phone }}</td>
                                <td class="text-muted">{{ $reg->created_at->format('M d, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No registrations found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Deposits -->
    <div class="col-lg-6" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-3 text-success"><i class="bi bi-arrow-down-circle-fill me-2"></i> Recent Deposits</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0 small">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>TXID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeposits as $dep)
                            <tr>
                                <td>
                                    <div class="fw-bold text-white">{{ $dep->user ? $dep->user->username : 'N/A' }}</div>
                                </td>
                                <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                                <td><code class="text-info">{{ Str::limit($dep->txid, 12) }}</code></td>
                                <td>
                                    @if($dep->status === 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5">Approved</span>
                                    @elseif($dep->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-0.5">Pending</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-0.5">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No deposits found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countElements = document.querySelectorAll('.countup');
        countElements.forEach(el => {
            const val = parseFloat(el.getAttribute('data-val'));
            const countUp = new countUp.CountUp(el, val, { duration: 2 });
            if (!countUp.error) countUp.start();
        });

        // Initialize Chart.js
        const ctx = document.getElementById('growthChart').getContext('2d');
        
        // Gradient for Deposits
        const depositGradient = ctx.createLinearGradient(0, 0, 0, 300);
        depositGradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)');
        depositGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        // Gradient for Withdrawals
        const withdrawGradient = ctx.createLinearGradient(0, 0, 0, 300);
        withdrawGradient.addColorStop(0, 'rgba(239, 68, 68, 0.5)');
        withdrawGradient.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Deposits ($)',
                        data: {!! json_encode($chartDeposits) !!},
                        borderColor: '#10b981',
                        backgroundColor: depositGradient,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Withdrawals ($)',
                        data: {!! json_encode($chartWithdrawals) !!},
                        borderColor: '#ef4444',
                        backgroundColor: withdrawGradient,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#f8fafc', font: { family: 'Inter' } } }
                },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
</script>
@endpush
