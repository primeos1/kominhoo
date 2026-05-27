<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Services\PaystackService;
use App\Services\PromoEngineService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WalletWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-paystack-signature', '');

        $paystack = new PaystackService();

        if (!$paystack->validateWebhookSignature($payload, $signature)) {
            Log::warning('Wallet webhook: invalid signature', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $event = $request->input('event');
        $data  = $request->input('data', []);

        if ($event === 'charge.success') {
            $this->handleChargeSuccess($data, $paystack);
        }

        // Always return 200 so Paystack stops retrying on non-wallet events
        return response()->json(['message' => 'ok'], 200);
    }

    private function handleChargeSuccess(array $data, PaystackService $paystack): void
    {
        $reference = $data['reference'] ?? null;
        if (!$reference) {
            return;
        }

        // Only process wallet deposit references
        if (!str_starts_with($reference, 'WLT-DEP-')) {
            Log::info('Wallet webhook: skipping non-wallet reference', ['ref' => $reference]);
            return;
        }

        $pendingTx = WalletTransaction::where('reference', $reference)->first();
        if (!$pendingTx) {
            Log::warning('Wallet webhook: pending transaction not found', ['ref' => $reference]);
            return;
        }

        // Idempotency — already handled
        if ($pendingTx->status === 'successful') {
            Log::info('Wallet webhook: already processed', ['ref' => $reference]);
            return;
        }

        // Verify directly with Paystack — never trust webhook payload alone
        $verified = $paystack->verifyTransaction($reference);
        if (!$verified) {
            Log::error('Wallet webhook: Paystack verification failed', ['ref' => $reference]);
            $pendingTx->update(['status' => 'failed']);
            return;
        }

        // Verify payment status
        if (($verified['status'] ?? '') !== 'success') {
            Log::warning('Wallet webhook: transaction not success status', [
                'ref'    => $reference,
                'status' => $verified['status'] ?? 'unknown',
            ]);
            return;
        }

        // Verify currency
        if (strtoupper($verified['currency'] ?? '') !== 'NGN') {
            Log::error('Wallet webhook: wrong currency', ['ref' => $reference, 'currency' => $verified['currency'] ?? null]);
            $pendingTx->update(['status' => 'failed']);
            return;
        }

        // Verify amount matches expected — tolerance ₦1 for floating point
        if (!$paystack->amountMatches($verified, (float) $pendingTx->amount)) {
            Log::error('Wallet webhook: amount mismatch', [
                'ref'           => $reference,
                'expected_ngn'  => $pendingTx->amount,
                'received_kobo' => $verified['amount'] ?? null,
            ]);
            $pendingTx->update(['status' => 'failed']);
            return;
        }

        try {
            $completedTx = WalletService::completePendingDeposit($pendingTx, [
                'paystack_id'      => $verified['id'] ?? null,
                'channel'          => $verified['channel'] ?? null,
                'gateway_response' => $verified['gateway_response'] ?? null,
                'paid_at'          => $verified['paid_at'] ?? null,
                'ip_address'       => $verified['ip_address'] ?? null,
            ]);

            $wallet = $completedTx->wallet()->with('user')->first();
            $user   = $wallet->user;

            // Grant first deposit bonus if this is their first completed deposit
            $depositCount = WalletTransaction::where('wallet_id', $wallet->id)
                ->where('category', 'deposit')
                ->where('status', 'successful')
                ->count();

            if ($depositCount === 1) {
                PromoEngineService::grantFirstDepositBonus($user, (float) $completedTx->amount);
            }

            Log::info('Wallet webhook: deposit completed', [
                'ref'     => $reference,
                'user_id' => $user->id,
                'amount'  => $completedTx->amount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Wallet webhook: failed to complete deposit', [
                'ref'   => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
