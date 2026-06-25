<?php

namespace App\Services;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Setting;

class ReferralService
{
    /**
     * Distribute commission up to 10 levels
     */
    public function distributeCommission(User $user, $investmentAmount)
    {
        // 20% Direct Reward
        $directPercentage = Setting::getVal('direct_reward_percent', 20);
        $directReward = ($investmentAmount * $directPercentage) / 100;

        $referrer = $user->referrer;
        if ($referrer) {
            $this->creditCommission($referrer, $directReward, 'Direct Reward (Level 1)', $user->id);
            
            // Multi-level from Level 2 to 10. (Level 1 is already handled as Direct Reward? 
            // Wait, the plan says:
            // DIRECT REWARD: 20% instant
            // MULTI LEVEL (10 levels): Level 1=5%, Level 2=4%... 
            // If Direct is separate, it means referrer gets 20% + 5%? Let's assume yes, or we can handle level 1 as 5%.
            // The prompt says: "Direct Reward (20%)" and "Level commissions (10 levels)". 
            // Let's loop 10 levels. 
            // The user's referrer is Level 1.
            
            $levels = [
                1 => Setting::getVal('referral_level_1', 5),
                2 => Setting::getVal('referral_level_2', 4),
                3 => Setting::getVal('referral_level_3', 3),
                4 => Setting::getVal('referral_level_4', 3),
                5 => Setting::getVal('referral_level_5', 2),
                6 => Setting::getVal('referral_level_6', 2),
                7 => Setting::getVal('referral_level_7', 1),
                8 => Setting::getVal('referral_level_8', 1),
                9 => Setting::getVal('referral_level_9', 1),
                10 => Setting::getVal('referral_level_10', 1),
            ];

            $currentUpline = $referrer;
            for ($i = 1; $i <= 10; $i++) {
                if (!$currentUpline) break;
                
                $percent = $levels[$i] ?? 0;
                if ($percent > 0) {
                    $commission = ($investmentAmount * $percent) / 100;
                    $this->creditCommission($currentUpline, $commission, "Level $i Commission", $user->id);
                }

                // Move up
                $currentUpline = $currentUpline->referrer;
            }
        }
    }

    private function creditCommission($upline, $amount, $description, $fromUserId)
    {
        $wallet = $upline->wallet;
        $wallet->referral_balance += $amount;
        $wallet->save();

        Transaction::create([
            'user_id' => $upline->id,
            'type' => 'commission',
            'amount' => $amount,
            'wallet_type' => 'referral_balance',
            'status' => 'completed',
            'description' => "$description from User #$fromUserId",
            'reference_id' => $fromUserId
        ]);
    }
}
