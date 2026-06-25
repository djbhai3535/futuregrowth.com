<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NOWPaymentsService
{
    protected $apiKey;
    protected $ipnSecret;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('NOWPAYMENTS_API_KEY');
        $this->ipnSecret = env('NOWPAYMENTS_IPN_SECRET');
        $this->apiUrl = 'https://api.nowpayments.io/v1';
    }

    /**
     * Create payment request with NOWPayments gateway
     */
    public function createPayment($amount, $orderId, $callbackUrl)
    {
        if (empty($this->apiKey)) {
            Log::error('NOWPayments API Key is not set in environment variables.');
            return null;
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/payment', [
            'price_amount' => $amount,
            'price_currency' => 'usd',
            'pay_amount' => $amount,
            'pay_currency' => 'usdttrc20',
            'order_id' => $orderId,
            'order_description' => 'USDT Deposit order #' . $orderId,
            'ipn_callback_url' => $callbackUrl,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('NOWPayments API payment creation failed', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        return null;
    }

    /**
     * Verify NOWPayments Instant Payment Notification signature
     */
    public function verifyIPN(array $payload, $signature)
    {
        if (empty($signature) || empty($this->ipnSecret)) {
            Log::warning('NOWPayments verification aborted: Missing signature or IPN secret.');
            return false;
        }

        // Sort keys alphabetically
        ksort($payload);
        
        // Remove signature key if present inside payload keys
        unset($payload['signature']);

        $serializedPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        $calculatedSignature = hash_hmac('sha512', $serializedPayload, $this->ipnSecret);

        return hash_equals($calculatedSignature, $signature);
    }
}
