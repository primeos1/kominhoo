<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key', '');
        $this->baseUrl   = config('services.paystack.base_url', 'https://api.paystack.co');
    }

    /**
     * Verify a transaction by reference.
     * Returns the transaction data array on success, null on failure.
     */
    public function verifyTransaction(string $reference): ?array
    {
        if (empty($this->secretKey)) {
            Log::warning('Paystack secret key is not configured.');
            return null;
        }

        try {
            $response = Http::withToken($this->secretKey)
                ->timeout(10)
                ->get("{$this->baseUrl}/transaction/verify/{$reference}");

            if (!$response->successful()) {
                Log::warning('Paystack verify HTTP error', [
                    'reference' => $reference,
                    'status'    => $response->status(),
                ]);
                return null;
            }

            $body = $response->json();

            if (!($body['status'] ?? false) || ($body['data']['status'] ?? '') !== 'success') {
                return null;
            }

            return $body['data'];
        } catch (\Exception $e) {
            Log::error('Paystack verify exception', [
                'reference' => $reference,
                'error'     => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Confirm amount paid matches expected (in NGN, not kobo).
     */
    public function amountMatches(array $txData, float $expectedNgn, float $toleranceNgn = 1.0): bool
    {
        $paidKobo    = (int) ($txData['amount'] ?? 0);
        $paidNgn     = $paidKobo / 100;
        $expectedNgn = (float) $expectedNgn;

        return abs($paidNgn - $expectedNgn) <= $toleranceNgn;
    }

    /**
     * Initialize a Paystack transaction. Returns the response data on success, null on failure.
     */
    public function initializeTransaction(string $email, float $amountNgn, string $reference, array $options = []): ?array
    {
        if (empty($this->secretKey)) {
            Log::warning('Paystack secret key is not configured.');
            return null;
        }

        try {
            $payload = array_merge([
                'email'     => $email,
                'amount'    => (int) round($amountNgn * 100), // kobo
                'reference' => $reference,
                'currency'  => 'NGN',
            ], $options);

            $response = Http::withToken($this->secretKey)
                ->timeout(10)
                ->post("{$this->baseUrl}/transaction/initialize", $payload);

            if (!$response->successful()) {
                Log::warning('Paystack initialize HTTP error', [
                    'reference' => $reference,
                    'status'    => $response->status(),
                    'body'      => $response->body(),
                ]);
                return null;
            }

            $body = $response->json();

            if (!($body['status'] ?? false)) {
                return null;
            }

            return $body['data'];
        } catch (\Exception $e) {
            Log::error('Paystack initialize exception', [
                'reference' => $reference,
                'error'     => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Validate a Paystack webhook signature.
     */
    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $secret = config('services.paystack.webhook_secret', '');
        if (empty($secret)) {
            return true; // not configured — skip validation in dev
        }

        $computed = hash_hmac('sha512', $payload, $secret);
        return hash_equals($computed, $signature);
    }
}
