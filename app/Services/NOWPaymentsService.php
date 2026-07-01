<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NOWPaymentsService
{
    /**
     * Get API Key dynamically from settings
     */
    protected function getApiKey()
    {
        return setting('nowpayments_api_key', env('NOWPAYMENTS_API_KEY'));
    }

    /**
     * Get IPN Secret dynamically from settings
     */
    protected function getIpnSecret()
    {
        return setting('nowpayments_ipn_secret', env('NOWPAYMENTS_IPN_SECRET'));
    }

    /**
     * Get API URL dynamically based on Sandbox/Live setting
     */
    protected function getApiUrl()
    {
        $sandbox = setting('nowpayments_sandbox_mode', 0);
        return $sandbox == 1 ? 'https://api-sandbox.nowpayments.io/v1' : 'https://api.nowpayments.io/v1';
    }

    /**
     * Create payment request with NOWPayments gateway
     */
    public function createPayment($amount, $orderId, $callbackUrl)
    {
        $apiKey = $this->getApiKey();
        $apiUrl = $this->getApiUrl();

        if (empty($apiKey)) {
            Log::error('NOWPayments API Key is not set in settings.');
            return null;
        }

        $coin = setting('nowpayments_default_coin', 'usdt');
        $network = setting('nowpayments_default_network', 'trc20');
        $payCurrency = strtolower($coin . $network);

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl . '/payment', [
            'price_amount' => $amount,
            'price_currency' => 'usd',
            'pay_amount' => $amount,
            'pay_currency' => $payCurrency,
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
        $ipnSecret = $this->getIpnSecret();

        if (empty($signature) || empty($ipnSecret)) {
            Log::warning('NOWPayments verification aborted: Missing signature or IPN secret.');
            return false;
        }

        // Sort keys alphabetically
        ksort($payload);
        
        // Remove signature key if present inside payload keys
        unset($payload['signature']);

        $serializedPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        $calculatedSignature = hash_hmac('sha512', $serializedPayload, $ipnSecret);

        return hash_equals($calculatedSignature, $signature);
    }
}
