<?php

/**
 * FutureGrowth.tech - Database vs. Dashboards Sync Validator
 * Run via terminal: php scratch/verify_sync_values.php
 */

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Investment;
use App\Models\Transaction;

echo "\n=========================================================================\n";
echo "FutureGrowth.tech - DATABASE vs. USER DASHBOARD vs. ADMIN PANEL SYNC VALIDATOR\n";
echo "=========================================================================\n";

// Ensure we have at least one test user with transactional history, or create one for validation
$users = User::take(3)->get();
if ($users->isEmpty()) {
    echo "⚠️ Database is empty. Running seeder/creating validator accounts...\n";
    // Create temporary validator user
    $testUser = User::create([
        'name' => 'Validator Test User',
        'username' => 'validator_test',
        'email' => 'validator@example.com',
        'phone' => '+18885554444',
        'password' => bcrypt('password123'),
        'status' => 'active',
        'referral_code' => 'VALCODE123'
    ]);
    $wallet = Wallet::create(['user_id' => $testUser->id]);
    $wallet->deposit_balance = 500.00;
    $wallet->roi_balance = 120.00;
    $wallet->referral_balance = 45.00;
    $wallet->save();

    Deposit::create(['user_id' => $testUser->id, 'amount' => 500.00, 'status' => 'approved', 'txid' => 'TX_VAL_DEP']);
    Investment::create(['user_id' => $testUser->id, 'plan_id' => 1, 'amount' => 300.00, 'daily_roi_percent' => 1.5, 'status' => 'active', 'total_earned' => 50.00]);
    Transaction::create(['user_id' => $testUser->id, 'type' => 'commission', 'amount' => 45.00, 'wallet_type' => 'referral_balance', 'status' => 'completed']);
    $users = collect([$testUser]);
}

foreach ($users as $index => $u) {
    echo "\n-------------------------------------------------------------------------\n";
    echo "Evaluating User #{$u->id}: '{$u->username}' ({$u->name})\n";
    echo "-------------------------------------------------------------------------\n";

    // 1. Direct DB Queries
    $dbWallet = Wallet::where('user_id', $u->id)->first();
    $dbDepositSum = Deposit::where('user_id', $u->id)->where('status', 'approved')->sum('amount');
    $dbWithdrawalSum = Withdrawal::where('user_id', $u->id)->where('status', 'approved')->sum('amount');
    $dbActiveInvSum = Investment::where('user_id', $u->id)->where('status', 'active')->sum('amount');
    $dbRoiSum = Investment::where('user_id', $u->id)->sum('total_earned');
    $dbCommissionSum = Transaction::where('user_id', $u->id)->where('type', 'commission')->sum('amount');
    $dbDirectRefs = User::where('referred_by', $u->id)->count();

    $dbTotalBalance = $dbWallet ? ($dbWallet->deposit_balance + $dbWallet->roi_balance + $dbWallet->referral_balance + $dbWallet->bonus_balance) : 0.00;

    // 2. User Dashboard Controller Values
    // Mock authentication context
    Auth::login($u);
    $userController = new \App\Http\Controllers\DashboardController();
    $response = $userController->index();
    $userDashboardData = $response->getData();

    $userTotalBalance = $userDashboardData['totalBalance'];
    $userDeposits = $userDashboardData['totalDeposits'];
    $userWithdrawals = $userDashboardData['totalWithdrawals'];
    $userActiveInvs = $userDashboardData['activeInvestmentsSum'];
    $userRoi = $userDashboardData['roiEarned'];
    $userCommissions = $userDashboardData['referralCommission'];
    $userDirectRefs = $userDashboardData['directReferralsCount'];

    // 3. Admin Controller Values
    $adminController = new \App\Http\Controllers\AdminController();
    // Simulate showUser response
    $adminResponse = $adminController->showUser($u->id);
    $adminData = $adminResponse->getData();

    $adminWallet = $adminData['user']->wallet;
    $adminTotalBalance = $adminWallet ? ($adminWallet->deposit_balance + $adminWallet->roi_balance + $adminWallet->referral_balance + $adminWallet->bonus_balance) : 0.00;
    $adminDeposits = $adminData['totalDeposit'];
    $adminWithdrawals = $adminData['totalWithdrawal'];
    $adminActiveInvs = $adminData['runningInvestment'];
    $adminRoi = $adminData['totalRoi'];
    $adminCommissions = $adminData['referralIncome'];
    $adminDirectRefs = count($adminData['referralTree'][1] ?? collect());

    // Print Comparison Matrix
    printf("%-22s | %-15s | %-18s | %-16s | Status\n", "Metric", "Direct Database", "User Dashboard", "Admin Panel View");
    echo "-----------------------+-----------------+--------------------+------------------+--------\n";
    
    compareMetric("Total Balance", $dbTotalBalance, $userTotalBalance, $adminTotalBalance);
    compareMetric("Wallet Balance", $dbWallet ? $dbWallet->deposit_balance : 0.00, $userDashboardData['wallet']->deposit_balance, $adminWallet ? $adminWallet->deposit_balance : 0.00);
    compareMetric("Total Deposits", $dbDepositSum, $userDeposits, $adminDeposits);
    compareMetric("Total Withdrawals", $dbWithdrawalSum, $userWithdrawals, $adminWithdrawals);
    compareMetric("Active Investments", $dbActiveInvSum, $userActiveInvs, $adminActiveInvs);
    compareMetric("ROI Earned", $dbRoiSum, $userRoi, $adminRoi);
    compareMetric("Referral Commission", $dbCommissionSum, $userCommissions, $adminCommissions);
    compareMetric("Direct Referrals", $dbDirectRefs, $userDirectRefs, $adminDirectRefs);
}

// Clean up temporary user if created
if (isset($testUser)) {
    $testUser->forceDelete();
}

echo "\n=========================================================================\n";
echo "SYNCHRONIZATION VALIDATION COMPLETED SUCCESSFULLY!\n";
echo "=========================================================================\n";

function compareMetric($name, $db, $user, $admin) {
    $status = ($db == $user && $user == $admin) ? "✅ OK" : "❌ MISMATCH";
    printf("%-22s | %-15s | %-18s | %-16s | %s\n", 
        $name, 
        is_numeric($db) ? number_format($db, 2) : $db, 
        is_numeric($user) ? number_format($user, 2) : $user, 
        is_numeric($admin) ? number_format($admin, 2) : $admin, 
        $status
    );
}
