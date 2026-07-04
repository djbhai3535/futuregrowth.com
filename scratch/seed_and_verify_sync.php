<?php

/**
 * FutureGrowth.tech - 10 Real Users Database vs. Dashboard Synchronization Validator
 * Run via terminal: php scratch/seed_and_verify_sync.php
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
echo "FutureGrowth.tech - 10 USERS COMPLEX SYNC VALIDATION SUITE\n";
echo "=========================================================================\n";

// 1. Clean previous sync validator users to avoid pollution
User::where('username', 'like', 'sync_user_%')->forceDelete();

echo "Seeding 10 users in a 10-level recursive downline tree...\n";

$users = [];
$referrerId = null;

// Seed 10 users sequentially where User 1 refers User 2, User 2 refers User 3, etc.
for ($i = 1; $i <= 10; $i++) {
    $username = "sync_user_{$i}";
    $user = User::create([
        'name' => "Sync User {$i}",
        'username' => $username,
        'email' => "{$username}@example.com",
        'phone' => '+1888999' . str_pad($i, 4, '0', STR_PAD_LEFT),
        'password' => bcrypt('password123'),
        'status' => 'active',
        'referral_code' => "REF_SYNC_{$i}",
        'referred_by' => $referrerId
    ]);

    // Create Wallet
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'deposit_balance' => 0,
        'roi_balance' => 0,
        'referral_balance' => 0,
        'bonus_balance' => 0
    ]);

    $referrerId = $user->id;
    $users[] = $user;
}

echo "Generating transaction history, deposits, investments, and payouts for all 10 users...\n";

foreach ($users as $index => $u) {
    $i = $index + 1;
    $wallet = $u->wallet;

    // A. Seed Deposits
    $depAmount1 = $i * 150.00;
    $depAmount2 = $i * 100.00;
    Deposit::create(['user_id' => $u->id, 'amount' => $depAmount1, 'status' => 'approved', 'txid' => "TX_DEP_{$i}_A"]);
    Deposit::create(['user_id' => $u->id, 'amount' => $depAmount2, 'status' => 'approved', 'txid' => "TX_DEP_{$i}_B"]);
    Deposit::create(['user_id' => $u->id, 'amount' => 50.00, 'status' => 'pending', 'txid' => "TX_DEP_{$i}_C"]);
    
    $wallet->deposit_balance = $depAmount1 + $depAmount2;

    // B. Seed Investments (Active and Completed)
    $invActive = $i * 80.00;
    $invCompleted = $i * 50.00;
    Investment::create(['user_id' => $u->id, 'plan_id' => 1, 'amount' => $invActive, 'daily_roi_percent' => 1.5, 'status' => 'active', 'total_earned' => $i * 10.00]);
    Investment::create(['user_id' => $u->id, 'plan_id' => 1, 'amount' => $invCompleted, 'daily_roi_percent' => 1.5, 'status' => 'completed', 'total_earned' => $invCompleted * 3]);

    $wallet->deposit_balance -= ($invActive + $invCompleted);

    // C. Seed Withdrawals
    if ($i > 2) {
        $withAmount = $i * 20.00;
        Withdrawal::create(['user_id' => $u->id, 'amount' => $withAmount, 'fee' => $withAmount * 0.05, 'net_amount' => $withAmount * 0.95, 'wallet_address' => 'TRC20_WITH_ADDR', 'wallet_type' => 'roi_balance', 'status' => 'approved']);
        Withdrawal::create(['user_id' => $u->id, 'amount' => 10.00, 'fee' => 0.50, 'net_amount' => 9.50, 'wallet_address' => 'TRC20_WITH_ADDR', 'wallet_type' => 'roi_balance', 'status' => 'pending']);
    }

    // D. Seed ROI History & Wallet Payouts
    $roiEarnedTotal = ($i * 10.00) + ($invCompleted * 3);
    $wallet->roi_balance = $roiEarnedTotal;
    if ($i > 2) {
        $wallet->roi_balance -= ($i * 20.00); // subtract approved withdrawals
    }
    
    Transaction::create(['user_id' => $u->id, 'type' => 'roi', 'amount' => $i * 10.00, 'wallet_type' => 'roi_balance', 'status' => 'completed']);
    Transaction::create(['user_id' => $u->id, 'type' => 'roi', 'amount' => $invCompleted * 3, 'wallet_type' => 'roi_balance', 'status' => 'completed']);

    // E. Seed Referral History & Commissions
    $refCommissionTotal = $i * 15.00;
    $wallet->referral_balance = $refCommissionTotal;
    Transaction::create(['user_id' => $u->id, 'type' => 'commission', 'amount' => $refCommissionTotal, 'wallet_type' => 'referral_balance', 'status' => 'completed']);

    $wallet->save();
}

echo "Seeding completed. Evaluating all 10 users and generating verification matrix...\n";

$dashboardController = new \App\Http\Controllers\DashboardController();
$adminController = new \App\Http\Controllers\AdminController();

foreach ($users as $index => $u) {
    $i = $index + 1;
    echo "\n=========================================================================\n";
    echo "Sync Verification for User #{$i}: '{$u->username}'\n";
    echo "=========================================================================\n";

    // 1. Boot User Dashboard to process ROI distribution on page load
    Auth::login($u);
    $response = $dashboardController->index();
    $userDashboardData = $response->getData();
    $u->refresh();

    // 2. Direct DB Queries
    $dbWallet = Wallet::where('user_id', $u->id)->first();
    $dbDepositSum = Deposit::where('user_id', $u->id)->where('status', 'approved')->sum('amount');
    $dbWithdrawalSum = Withdrawal::where('user_id', $u->id)->where('status', 'approved')->sum('amount');
    $dbActiveInvSum = Investment::where('user_id', $u->id)->where('status', 'active')->sum('amount');
    $dbRoiSum = Investment::where('user_id', $u->id)->sum('total_earned');
    $dbCommissionSum = Transaction::where('user_id', $u->id)->where('type', 'commission')->sum('amount');
    $dbDirectRefs = User::where('referred_by', $u->id)->count();

    // 10-level recursive team counts (cycle-safe)
    $dbTeamSize = 0;
    $dbVisitedUserIds = [$u->id];
    $currentLevelReferrals = User::where('referred_by', $u->id)->get();
    $dbLevelCounts = [];
    for ($lvl = 1; $lvl <= 10; $lvl++) {
        if ($currentLevelReferrals->isEmpty()) {
            $dbLevelCounts[$lvl] = 0;
            continue;
        }
        $currentLevelReferrals = $currentLevelReferrals->whereNotIn('id', $dbVisitedUserIds);
        if ($currentLevelReferrals->isEmpty()) {
            $dbLevelCounts[$lvl] = 0;
            continue;
        }
        $dbLevelCounts[$lvl] = $currentLevelReferrals->count();
        $dbTeamSize += $currentLevelReferrals->count();
        $userIds = $currentLevelReferrals->pluck('id')->toArray();
        $dbVisitedUserIds = array_merge($dbVisitedUserIds, $userIds);
        $currentLevelReferrals = User::whereIn('referred_by', $userIds)->get();
    }

    $dbTotalBalance = $dbWallet ? ($dbWallet->deposit_balance + $dbWallet->roi_balance + $dbWallet->referral_balance + $dbWallet->bonus_balance) : 0.00;

    $userTotalBalance = $userDashboardData['totalBalance'];
    $userDeposits = $userDashboardData['totalDeposits'];
    $userWithdrawals = $userDashboardData['totalWithdrawals'];
    $userActiveInvs = $userDashboardData['activeInvestmentsSum'];
    $userRoi = $userDashboardData['roiEarned'];
    $userCommissions = $userDashboardData['referralCommission'];
    $userDirectRefs = $userDashboardData['directReferralsCount'];
    $userTeamSize = $userDashboardData['teamSize'];

    // 3. Admin Controller Values
    $adminResponse = $adminController->showUser($u->id);
    $adminData = $adminResponse->getData();

    $adminWallet = $adminData['user']->wallet;
    $adminTotalBalance = $adminWallet ? ($adminWallet->deposit_balance + $adminWallet->roi_balance + $adminWallet->referral_balance + $adminWallet->bonus_balance) : 0.00;
    $adminDeposits = $adminData['totalDeposit'];
    $adminWithdrawals = $adminData['totalWithdrawal'];
    $adminActiveInvs = $adminData['runningInvestment'];
    $adminRoi = $adminData['totalRoi'];
    $adminCommissions = $adminData['referralIncome'];
    $adminTeamSize = $adminData['teamSize'];
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
    compareMetric("Total Team Size", $dbTeamSize, $userTeamSize, $adminTeamSize);

    // Level-by-level verification
    for ($lvl = 1; $lvl <= 10; $lvl++) {
        $dbLvlVal = $dbLevelCounts[$lvl];
        
        // Fetch User Team Page view data
        $teamResponse = $dashboardController->team();
        $teamData = $teamResponse->getData();
        $userLvlVal = $teamData['levelsData'][$lvl]['total_users'] ?? 0;
        
        // Fetch Admin referrals Explorer view data
        $adminRefReq = new \Illuminate\Http\Request();
        $adminRefReq->merge(['user_id' => $u->id]);
        $adminRefResponse = $adminController->referrals($adminRefReq);
        $adminRefData = $adminRefResponse->getData();
        $adminLvlVal = isset($adminRefData['levelsData'][$lvl]['users']) ? count($adminRefData['levelsData'][$lvl]['users']) : 0;

        compareMetric("Level {$lvl} Team Count", $dbLvlVal, $userLvlVal, $adminLvlVal);
    }
}

// Clean up test data
User::where('username', 'like', 'sync_user_%')->forceDelete();

echo "\n=========================================================================\n";
echo "10 COMPLEX USERS SYNCHRONIZATION VALIDATION COMPLETED SUCCESSFULLY!\n";
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
