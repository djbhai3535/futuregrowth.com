<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    $faqs = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->get();
    $plans = \App\Models\Plan::where('status', 'active')->orderBy('min_amount')->get();
    $stats = [
        'users' => max(15, \App\Models\User::count()),
        'deposits' => max(2500, \App\Models\Deposit::where('status', 'approved')->sum('amount')),
        'levels' => 10,
        'multiplier' => setting('enable_return_multiplier', 1) ? (setting('investment_return_multiplier', 3) * 100) : 300,
    ];
    return view('welcome', compact('faqs', 'plans', 'stats'));
})->name('home');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification
Route::get('/verify-email', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/verify-email/resend', [AuthController::class, 'resendVerificationCode'])->name('verification.resend');

// Forgot & Reset Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Admin 2FA Verification
Route::get('/admin-login/2fa', [AuthController::class, 'showAdmin2FAForm'])->name('admin.2fa.show');
Route::post('/admin-login/2fa', [AuthController::class, 'verifyAdmin2FA'])->name('admin.2fa.verify');

Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('/risk', [\App\Http\Controllers\PageController::class, 'risk'])->name('risk');
Route::get('/contact', [\App\Http\Controllers\PageController::class, 'contact'])->name('contact');

Route::middleware(['auth', \App\Http\Middleware\EnsureEmailIsVerified::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/team', [DashboardController::class, 'team'])->name('dashboard.team');
    Route::get('/dashboard/history', [DashboardController::class, 'history'])->name('dashboard.history');
    
    Route::get('/dashboard/deposits', [\App\Http\Controllers\DepositController::class, 'create'])->name('dashboard.deposits');
    Route::post('/dashboard/deposits', [\App\Http\Controllers\DepositController::class, 'store'])->name('dashboard.deposits.store');
    Route::post('/dashboard/deposits/nowpayments', [\App\Http\Controllers\NOWPaymentsController::class, 'initiatePayment'])->name('dashboard.deposits.nowpayments');
    
    Route::get('/dashboard/withdrawals', [\App\Http\Controllers\WithdrawalController::class, 'create'])->name('dashboard.withdrawals');
    Route::post('/dashboard/withdrawals', [\App\Http\Controllers\WithdrawalController::class, 'store'])->name('dashboard.withdrawals.store');
    
    Route::get('/dashboard/investments', [\App\Http\Controllers\InvestmentController::class, 'index'])->name('dashboard.investments');
    Route::post('/dashboard/investments', [\App\Http\Controllers\InvestmentController::class, 'store'])->name('dashboard.investments.store');
    
    // Profile & Settings
    Route::get('/dashboard/profile', [\App\Http\Controllers\ProfileController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::get('/dashboard/settings', [\App\Http\Controllers\ProfileController::class, 'settings'])->name('dashboard.settings');
    Route::post('/dashboard/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('dashboard.password.update');

    // Support Tickets
    Route::post('/dashboard/tickets', [\App\Http\Controllers\DashboardController::class, 'storeTicket'])->name('dashboard.tickets.store');
});

use App\Http\Middleware\AdminMiddleware;

$adminSecret = 'admin-fg-secure';
try {
    $adminSecret = setting('admin_secret_path', 'admin-fg-secure');
} catch (\Exception $e) {}

// Disable standard /admin access
Route::any('/admin', function () {
    abort(404);
});

Route::middleware(['auth', AdminMiddleware::class])->prefix($adminSecret)->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');

    Route::get('/deposits', [\App\Http\Controllers\AdminController::class, 'deposits'])->name('deposits');
    Route::post('/deposits/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveDeposit'])->name('deposits.approve');
    Route::post('/deposits/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectDeposit'])->name('deposits.reject');
    
    Route::get('/withdrawals', [\App\Http\Controllers\AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [\App\Http\Controllers\AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/activate', [\App\Http\Controllers\AdminController::class, 'activateUser'])->name('users.activate');
    Route::post('/users/{id}/suspend', [\App\Http\Controllers\AdminController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{id}/ban', [\App\Http\Controllers\AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{id}/delete', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{id}/change-password', [\App\Http\Controllers\AdminController::class, 'changeUserPassword'])->name('users.change-password');
    Route::post('/users/{id}/reset-password', [\App\Http\Controllers\AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::post('/users/{id}/reset-password-auto', [\App\Http\Controllers\AdminController::class, 'resetUserPasswordAuto'])->name('users.reset-password-auto');
    Route::post('/users/{id}/verify-email', [\App\Http\Controllers\AdminController::class, 'verifyUserEmail'])->name('users.verify-email');
    
    // Extended Admin Actions & Referral Control routes
    Route::post('/users/{id}/adjust-wallet', [\App\Http\Controllers\AdminController::class, 'adjustWallet'])->name('users.adjust-wallet');
    Route::post('/deposits/manual', [\App\Http\Controllers\AdminController::class, 'addDeposit'])->name('deposits.manual');
    Route::post('/withdrawals/manual', [\App\Http\Controllers\AdminController::class, 'addWithdrawal'])->name('withdrawals.manual');
    Route::post('/investments/manual', [\App\Http\Controllers\AdminController::class, 'addInvestment'])->name('investments.manual');
    Route::post('/investments/{id}/complete', [\App\Http\Controllers\AdminController::class, 'completeInvestment'])->name('investments.complete');
    Route::post('/users/{id}/add-roi', [\App\Http\Controllers\AdminController::class, 'addRoi'])->name('users.add-roi');
    Route::post('/users/{id}/add-referral-bonus', [\App\Http\Controllers\AdminController::class, 'addReferralBonus'])->name('users.add-referral-bonus');
    Route::post('/users/change-sponsor', [\App\Http\Controllers\AdminController::class, 'changeSponsor'])->name('users.change-sponsor');
    Route::post('/users/remove-referral', [\App\Http\Controllers\AdminController::class, 'removeReferral'])->name('users.remove-referral');
    Route::post('/users/rebuild-tree', [\App\Http\Controllers\AdminController::class, 'rebuildReferralTree'])->name('users.rebuild-tree');
    Route::get('/referrals', [\App\Http\Controllers\AdminController::class, 'referrals'])->name('referrals');

    // Reports & Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\AdminController::class, 'auditLogs'])->name('audit-logs');
    Route::get('/reports', [\App\Http\Controllers\AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [\App\Http\Controllers\AdminController::class, 'exportReport'])->name('reports.export');
    Route::get('/plans', [\App\Http\Controllers\AdminController::class, 'plans'])->name('plans');
    Route::post('/plans', [\App\Http\Controllers\AdminController::class, 'storePlan'])->name('plans.store');
    Route::post('/plans/{id}/update', [\App\Http\Controllers\AdminController::class, 'updatePlan'])->name('plans.update');
    Route::post('/plans/{id}/destroy', [\App\Http\Controllers\AdminController::class, 'destroyPlan'])->name('plans.destroy');
    Route::post('/plans/{id}/toggle', [\App\Http\Controllers\AdminController::class, 'togglePlan'])->name('plans.toggle');
    
    // Admin Support Tickets
    Route::get('/tickets', [\App\Http\Controllers\AdminController::class, 'tickets'])->name('tickets');
    Route::post('/tickets/{id}/reply', [\App\Http\Controllers\AdminController::class, 'replyTicket'])->name('tickets.reply');
    Route::post('/tickets/{id}/close', [\App\Http\Controllers\AdminController::class, 'closeTicket'])->name('tickets.close');

    // Admin FAQs
    Route::get('/faqs', [\App\Http\Controllers\AdminController::class, 'faqs'])->name('faqs');
    Route::post('/faqs', [\App\Http\Controllers\AdminController::class, 'storeFaq'])->name('faqs.store');
    Route::post('/faqs/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateFaq'])->name('faqs.update');
    Route::post('/faqs/{id}/destroy', [\App\Http\Controllers\AdminController::class, 'destroyFaq'])->name('faqs.destroy');

    // Admin Documents
    Route::get('/documents', [\App\Http\Controllers\AdminController::class, 'documents'])->name('documents');
    Route::post('/documents', [\App\Http\Controllers\AdminController::class, 'storeDocument'])->name('documents.store');
    Route::post('/documents/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateDocument'])->name('documents.update');
    Route::post('/documents/{id}/destroy', [\App\Http\Controllers\AdminController::class, 'destroyDocument'])->name('documents.destroy');
});

Route::post('/payment/nowpayments/webhook', [\App\Http\Controllers\NOWPaymentsController::class, 'ipnCallback'])->name('nowpayments.webhook');
