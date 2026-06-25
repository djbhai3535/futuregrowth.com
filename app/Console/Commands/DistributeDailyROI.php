<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Investment;
use App\Models\Transaction;

class DistributeDailyROI extends Command
{
    protected $signature = 'roi:distribute';
    protected $description = 'Distributes daily ROI to active investments';

    public function handle()
    {
        // Get all active investments that haven't received ROI in the last 24h
        $investments = Investment::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('last_roi_at')
                      ->orWhere('last_roi_at', '<=', now()->subHours(24));
            })->get();

        $enableMultiplier = setting('enable_return_multiplier', true);
        $multiplier = setting('investment_return_multiplier', 3);

        foreach ($investments as $inv) {
            $roiAmount = ($inv->amount * $inv->daily_roi_percent) / 100;
            
            if ($enableMultiplier) {
                $maxReturn = $inv->amount * $multiplier;
                $remainingCapacity = $maxReturn - $inv->total_earned;

                if ($roiAmount >= $remainingCapacity) {
                    $roiAmount = $remainingCapacity;
                    $inv->status = 'completed'; // Cap reached
                }
            }
            
            if ($roiAmount > 0) {
                $wallet = $inv->user->wallet;
                $wallet->roi_balance += $roiAmount;
                $wallet->save();

                Transaction::create([
                    'user_id' => $inv->user_id,
                    'type' => 'roi',
                    'amount' => $roiAmount,
                    'wallet_type' => 'roi_balance',
                    'status' => 'completed',
                    'description' => "Daily ROI for Investment #" . $inv->id,
                    'reference_id' => $inv->id
                ]);

                $inv->total_earned += $roiAmount;
            }

            $inv->last_roi_at = now();
            $inv->save();
        }

        $this->info('Daily ROI distributed successfully.');
    }
}
