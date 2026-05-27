<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Services\PaystackService;
use App\Services\PromoEngineService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function show(Request $request)
    {
        $user   = $request->user();
        $wallet = WalletService::getOrCreate($user);

        return $this->apiResponse([
            'wallet' => [
                'id'                => $wallet->id,
                'available_balance' => (float) $wallet->available_balance,
                'locked_balance'    => (float) $wallet->locked_balance,
                'total_balance'     => $wallet->totalBalance(),
                'currency'          => $wallet->currency,
                'status'            => $wallet->status,
            ],
        ]);
    }

    public function transactions(Request $request)
    {
        $user    = $request->user();
        $wallet  = WalletService::getOrCreate($user);
        $perPage = min((int) $request->input('per_page', 15), 50);

        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->apiResponse($transactions);
    }

    public function initiateDeposit(Request $request)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:100|max:1000000',
            'callback_url' => 'nullable|url',
        ]);

        $user   = $request->user();
        $wallet = WalletService::getOrCreate($user);

        if (!$wallet->isActive()) {
            return $this->apiResponse(null, "Your wallet is {$wallet->status}. Please contact support.", false, 403);
        }

        $amount    = round((float) $request->input('amount'), 2);
        $reference = 'WLT-DEP-' . strtoupper(Str::random(20));

        // Create pending ledger entry before hitting Paystack
        $pendingTx = WalletService::createPendingDeposit($wallet, $amount, $reference, [
            'metadata' => ['user_id' => $user->id, 'initiated_at' => now()->toISOString()],
        ]);

        $paystackOptions = [];
        if ($request->filled('callback_url')) {
            $paystackOptions['callback_url'] = $request->input('callback_url');
        }

        $paystackOptions['metadata'] = [
            'wallet_id'      => $wallet->id,
            'user_id'        => $user->id,
            'purpose'        => 'wallet_deposit',
            'transaction_id' => $pendingTx->id,
        ];

        $paystack   = new PaystackService();
        $paystackTx = $paystack->initializeTransaction($user->email, $amount, $reference, $paystackOptions);

        if (!$paystackTx) {
            $pendingTx->update(['status' => 'failed']);
            return $this->apiResponse(null, 'Could not initialize payment. Please try again.', false, 502);
        }

        return $this->apiResponse([
            'reference'              => $reference,
            'authorization_url'      => $paystackTx['authorization_url'],
            'access_code'            => $paystackTx['access_code'],
            'amount'                 => $amount,
            'pending_transaction_id' => $pendingTx->id,
        ], 'Payment initialized. Redirect user to authorization_url.');
    }

    public function verifyDeposit(Request $request)
    {
        $request->validate(['reference' => 'required|string|max:100']);

        $user      = $request->user();
        $reference = $request->input('reference');
        $wallet    = WalletService::getOrCreate($user);

        // Idempotency — already credited (either flow)
        $existing = WalletTransaction::where('reference', $reference)
            ->where('status', 'successful')
            ->first();
        if ($existing) {
            return $this->apiResponse([
                'status'      => 'successful',
                'new_balance' => (float) $wallet->fresh()->available_balance,
                'amount'      => (float) $existing->amount,
            ], 'Deposit already completed.');
        }

        // Verify with Paystack
        $paystack = new PaystackService();
        $verified = $paystack->verifyTransaction($reference);

        if (!$verified || ($verified['status'] ?? '') !== 'success') {
            return $this->apiResponse(null, 'Payment not confirmed by Paystack. Please wait a moment and try again.', false, 402);
        }

        $paidAmountNgn = round(($verified['amount'] ?? 0) / 100, 2);

        try {
            $pendingTx = WalletTransaction::where('reference', $reference)
                ->where('wallet_id', $wallet->id)
                ->whereIn('status', ['pending'])
                ->first();

            if ($pendingTx) {
                // Pre-initiated flow — complete the pending record
                $completedTx = WalletService::completePendingDeposit($pendingTx, [
                    'paystack_id'      => $verified['id']               ?? null,
                    'channel'          => $verified['channel']          ?? null,
                    'gateway_response' => $verified['gateway_response'] ?? null,
                    'paid_at'          => $verified['paid_at']          ?? null,
                    'verified_via'     => 'frontend_callback',
                ]);
            } else {
                // Direct flow — no pending record, credit straight from Paystack verification
                $completedTx = WalletService::credit(
                    $wallet,
                    $paidAmountNgn,
                    'credit',
                    'paystack',
                    'deposit',
                    'Wallet top-up via Paystack',
                    [
                        'reference'                 => $reference,
                        'related_payment_reference' => $reference,
                        'metadata'                  => [
                            'paystack_id'      => $verified['id']               ?? null,
                            'channel'          => $verified['channel']          ?? null,
                            'gateway_response' => $verified['gateway_response'] ?? null,
                            'paid_at'          => $verified['paid_at']          ?? null,
                            'verified_via'     => 'frontend_callback_direct',
                        ],
                    ]
                );
            }

            $newBalance = (float) $wallet->fresh()->available_balance;

            // First deposit bonus
            $depositCount = WalletTransaction::where('wallet_id', $wallet->id)
                ->where('category', 'deposit')
                ->where('status', 'successful')
                ->count();
            if ($depositCount === 1) {
                PromoEngineService::grantFirstDepositBonus($user, (float) $completedTx->amount);
            }

            return $this->apiResponse([
                'status'      => 'successful',
                'new_balance' => $newBalance,
                'amount'      => (float) $completedTx->amount,
            ], 'Wallet credited successfully.');

        } catch (\Throwable $e) {
            return $this->apiResponse(null, 'Could not complete deposit: ' . $e->getMessage(), false, 500);
        }
    }

    public function payWithWallet(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:1',
            'order_id'    => 'nullable|integer',
            'description' => 'nullable|string|max:255',
        ]);

        $user   = $request->user();
        $wallet = WalletService::getOrCreate($user);
        $amount = round((float) $request->input('amount'), 2);

        try {
            $transaction = WalletService::debit(
                $wallet,
                $amount,
                'debit',
                'order_payment',
                'purchase',
                $request->input('description', 'Wallet payment for order'),
                ['related_order_id' => $request->input('order_id')]
            );

            return $this->apiResponse([
                'transaction'  => $transaction,
                'new_balance'  => (float) $wallet->fresh()->available_balance,
            ], 'Payment successful');
        } catch (\RuntimeException $e) {
            return $this->apiResponse(null, $e->getMessage(), false, 422);
        }
    }
}
