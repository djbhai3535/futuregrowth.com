<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NOWPaymentsService;
use App\Models\Deposit;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NOWPaymentsController extends Controller
{
    protected $nowPaymentsService;

    public function __construct(NOWPaymentsService $nowPaymentsService)
    {
        $this->nowPaymentsService = $nowPaymentsService;
    }

    /**
     * Initiate checkout payment request with NOWPayments
     */
    public function initiatePayment(Request $request)
    {
        $min = setting('nowpayments_min_deposit', setting('min_deposit', 25));
        $max = setting('nowpayments_max_deposit', setting('max_deposit', 50000));

        // Fetch enabled networks
        $trc20Enabled = setting('nowpayments_enable_trc20', 1) == 1;
        $bep20Enabled = setting('nowpayments_enable_bep20', 1) == 1;

        $allowedNetworks = [];
        if ($trc20Enabled) $allowedNetworks[] = 'trc20';
        if ($bep20Enabled) $allowedNetworks[] = 'bep20';

        if (empty($allowedNetworks)) {
            return back()->withErrors(['amount' => 'NOWPayments deposit is currently offline. No networks are enabled.']);
        }

        $request->validate([
            'amount' => "required|numeric|min:{$min}|max:{$max}",
            'network' => "required|in:" . implode(',', $allowedNetworks),
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $network = $request->network;

        // Generate temporary checkout transaction reference
        $tempTxid = 'NP_TEMP_' . uniqid();

        // Create initial pending deposit record
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'txid' => $tempTxid,
            'status' => 'pending'
        ]);

        // Define IPN callback URL
        $callbackUrl = route('nowpayments.webhook');

        // Call payment creation API
        $paymentData = $this->nowPaymentsService->createPayment($amount, $deposit->id, $callbackUrl, $network);

        if ($paymentData && isset($paymentData['payment_id'])) {
            // Update deposit with actual NOWPayments payment ID
            $deposit->update([
                'txid' => 'NOW_' . $paymentData['payment_id']
            ]);

            // Save reference and redirect to NOWPayments checkout page/invoice
            // If the gateway doesn't provide direct invoice link, we output the crypto address
            $checkoutUrl = $paymentData['invoice_url'] ?? null;
            if (!$checkoutUrl) {
                return redirect()->route('dashboard.history')->with('success', 'USDT TRC20 Wallet: ' . ($paymentData['pay_address'] ?? 'Loading...') . '. Transfer exactly: ' . ($paymentData['pay_amount'] ?? $amount) . ' USDT.');
            }

            return redirect()->away($checkoutUrl);
        }

        // Cleanup temporary record on failure
        $deposit->delete();

        return back()->withErrors(['amount' => 'Could not communicate with NOWPayments gateway. Please try again.']);
    }

    /**
     * Handle NOWPayments Instant Payment Notification Webhook Callbacks
     */
    public function ipnCallback(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('x-nowpayments-sig');

        Log::info('NOWPayments IPN Received', [
            'payload' => $payload,
            'signature' => $signature
        ]);

        // Verify Signature
        if (!$this->nowPaymentsService->verifyIPN($payload, $signature)) {
            Log::warning('NOWPayments signature verification failed.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $paymentId = $payload['payment_id'] ?? null;
        $paymentStatus = $payload['payment_status'] ?? null;

        if (!$paymentId || !$paymentStatus) {
            return response()->json(['error' => 'Invalid payload parameters'], 400);
        }

        // Find the deposit matching the payment ID
        $deposit = Deposit::where('txid', 'NOW_' . $paymentId)->first();

        if (!$deposit) {
            Log::warning('Deposit record not found for payment ID: ' . $paymentId);
            return response()->json(['error' => 'Deposit not found'], 404);
        }

        // Confirm transitions to success status
        $successStates = ['confirmed', 'sending', 'finished'];
        if (in_array($paymentStatus, $successStates)) {
            if ($deposit->status !== 'approved') {
                $deposit->status = 'approved';
                $deposit->save();

                // Credit user wallet
                $wallet = Wallet::where('user_id', $deposit->user_id)->first();
                if ($wallet) {
                    $wallet->deposit_balance += $deposit->amount;
                    $wallet->save();
                }

                // Log Transaction
                Transaction::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit',
                    'amount' => $deposit->amount,
                    'wallet_type' => 'deposit_balance',
                    'status' => 'completed',
                    'description' => 'Automatic USDT Deposit Approved via NOWPayments. ID: ' . $paymentId,
                    'reference_id' => $deposit->id
                ]);

                // Log Activity
                ActivityLog::create([
                    'user_id' => $deposit->user_id,
                    'action' => 'Automatic USDT Deposit approved via NOWPayments Webhook. ID: ' . $paymentId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                Log::info('Automatic Deposit successfully approved for User ID: ' . $deposit->user_id);
            }
        } elseif (in_array($paymentStatus, ['failed', 'expired'])) {
            if ($deposit->status === 'pending') {
                $deposit->status = 'rejected';
                $deposit->save();

                Log::info('Deposit failed/expired via NOWPayments. ID: ' . $paymentId);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
