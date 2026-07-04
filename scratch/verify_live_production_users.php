<?php

/**
 * FutureGrowth.tech - Live Production User Sync Validator (Non-destructive)
 * Upload this file to your VPS and run: php scratch/verify_live_production_users.php
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
echo "FutureGrowth.tech - LIVE PRODUCTION USERS SYNC VALIDATION SUITE\n";
echo "=========================================================================\n";

// Fetch the first 10 users from the database
$users = User::take(10)->get();

if ($users->isEmpty()) {
    echo "❌ ERROR: No existing users found in the database. Please ensure you are running this on the live VPS database.\n";
    exit(1);
}

echo "Found " . $users->count() . " existing production user(s). Running checks...\n";

$dashboardController = new \App\Http\Controllers\DashboardController();
$adminController = new \App\Http\Controllers\AdminController();

foreach ($users as $index => $u) {
    $num = $index + 1;
    echo "\n=========================================================================\n";
    echo "Live User #{$num}: ID: {$u->id} | Username: '{$u->username}' | Email: {$u->email}\n";
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
    $dbCompletedInvCount = Investment::where('user_id', $u->id)->where('status', 'completed')->count();
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

    // 3. User Dashboard Controller Values
    $userTotalBalance = $userDashboardData['totalBalance'];
    $userDeposits = $userDashboardData['totalDeposits'];
    $userWithdrawals = $userDashboardData['totalWithdrawals'];
    $userActiveInvs = $userDashboardData['activeInvestmentsSum'];
    $userCompletedInvs = $userDashboardData['completedInvestmentsCount'];
    $userRoi = $userDashboardData['roiEarned'];
    $userCommissions = $userDashboardData['referralCommission'];
    $userDirectRefs = $userDashboardData['directReferralsCount'];
    $userTeamSize = $userDashboardData['teamSize'];

    // 4. Admin Controller Values
    $adminResponse = $adminController->showUser($u->id);
    $adminData = $adminResponse->getData();

    $adminWallet = $adminData['user']->wallet;
    $adminTotalBalance = $adminWallet ? ($adminWallet->deposit_balance + $adminWallet->roi_balance + $adminWallet->referral_balance + $adminWallet->bonus_balance) : 0.00;
    $adminDeposits = $adminData['totalDeposit'];
    $adminWithdrawals = $adminData['totalWithdrawal'];
    $adminActiveInvs = $adminData['runningInvestment'];
    $adminCompletedInvs = count($adminData['investments']->where('status', 'completed'));
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
    compareMetric("Completed Investments", $dbCompletedInvCount, $userCompletedInvs, $adminCompletedInvs);
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

echo "\n=========================================================================\n";
echo "LIVE PRODUCTION USERS SYNCHRONIZATION VALIDATION COMPLETED!\n";
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
