<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletAuditLog;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletService
{
    public static function getOrCreate(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'available_balance' => 0.00,
                'locked_balance'    => 0.00,
                'currency'          => 'NGN',
                'status'            => 'active',
            ]
        );
    }

    /**
     * Atomically credit a wallet.
     * Creates ledger entry + updates balance + writes audit log inside one DB transaction.
     */
    public static function credit(
        Wallet $wallet,
        float  $amount,
        string $transactionType,
        string $source,
        string $category,
        string $description,
        array  $options = []
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Credit amount must be positive, got {$amount}");
        }

        return DB::transaction(function () use ($wallet, $amount, $transactionType, $source, $category, $description, $options) {
            // Acquire row-level lock to prevent race conditions
            $wallet = Wallet::lockForUpdate()->findOrFail($wallet->id);

            if (!$wallet->isActive()) {
                throw new \RuntimeException("Wallet {$wallet->id} is {$wallet->status} — cannot credit");
            }

            $balanceBefore = (float) $wallet->available_balance;
            $balanceAfter  = round($balanceBefore + $amount, 2);

            $transaction = WalletTransaction::create([
                'wallet_id'                 => $wallet->id,
                'reference'                 => $options['reference'] ?? self::generateReference(),
                'transaction_type'          => $transactionType,
                'source'                    => $source,
                'category'                  => $category,
                'amount'                    => $amount,
                'balance_before'            => $balanceBefore,
                'balance_after'             => $balanceAfter,
                'status'                    => 'successful',
                'description'               => $description,
                'idempotency_key'           => $options['idempotency_key'] ?? null,
                'related_order_id'          => $options['related_order_id'] ?? null,
                'related_payment_reference' => $options['related_payment_reference'] ?? null,
                'processed_by'              => $options['processed_by'] ?? null,
                'metadata'                  => $options['metadata'] ?? null,
            ]);

            $wallet->available_balance = $balanceAfter;
            $wallet->save();

            self::writeAudit($wallet, $transaction, 'credit', $options);

            return $transaction;
        });
    }

    /**
     * Atomically debit a wallet.
     * Throws RuntimeException on insufficient balance or inactive wallet.
     */
    public static function debit(
        Wallet $wallet,
        float  $amount,
        string $transactionType,
        string $source,
        string $category,
        string $description,
        array  $options = []
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Debit amount must be positive, got {$amount}");
        }

        return DB::transaction(function () use ($wallet, $amount, $transactionType, $source, $category, $description, $options) {
            $wallet = Wallet::lockForUpdate()->findOrFail($wallet->id);

            if (!$wallet->isActive()) {
                throw new \RuntimeException("Wallet {$wallet->id} is {$wallet->status} — cannot debit");
            }

            $balanceBefore = (float) $wallet->available_balance;

            if ($balanceBefore < $amount) {
                throw new \RuntimeException('Insufficient wallet balance');
            }

            $balanceAfter = round($balanceBefore - $amount, 2);

            $transaction = WalletTransaction::create([
                'wallet_id'                 => $wallet->id,
                'reference'                 => $options['reference'] ?? self::generateReference(),
                'transaction_type'          => $transactionType,
                'source'                    => $source,
                'category'                  => $category,
                'amount'                    => $amount,
                'balance_before'            => $balanceBefore,
                'balance_after'             => $balanceAfter,
                'status'                    => 'successful',
                'description'               => $description,
                'idempotency_key'           => $options['idempotency_key'] ?? null,
                'related_order_id'          => $options['related_order_id'] ?? null,
                'related_payment_reference' => $options['related_payment_reference'] ?? null,
                'processed_by'              => $options['processed_by'] ?? null,
                'metadata'                  => $options['metadata'] ?? null,
            ]);

            $wallet->available_balance = $balanceAfter;
            $wallet->save();

            self::writeAudit($wallet, $transaction, 'debit', $options);

            return $transaction;
        });
    }

    /**
     * Creates a pending ledger entry for an outbound Paystack deposit.
     * Balance is NOT updated here — only after webhook confirms payment.
     */
    public static function createPendingDeposit(
        Wallet $wallet,
        float  $amount,
        string $reference,
        array  $options = []
    ): WalletTransaction {
        return WalletTransaction::create([
            'wallet_id'        => $wallet->id,
            'reference'        => $reference,
            'transaction_type' => 'credit',
            'source'           => 'paystack',
            'category'         => 'deposit',
            'amount'           => $amount,
            'balance_before'   => (float) $wallet->available_balance,
            'balance_after'    => (float) $wallet->available_balance, // updated on completion
            'status'           => 'pending',
            'description'      => 'Wallet top-up via Paystack',
            'metadata'         => $options['metadata'] ?? null,
        ]);
    }

    /**
     * Completes a pending deposit after Paystack webhook verification.
     * Idempotent — safe to call on already-completed transactions.
     */
    public static function completePendingDeposit(
        WalletTransaction $pendingTx,
        array $paystackData = []
    ): WalletTransaction {
        // Idempotency guard — already processed
        if ($pendingTx->status === 'successful') {
            return $pendingTx;
        }

        return DB::transaction(function () use ($pendingTx, $paystackData) {
            $wallet = Wallet::lockForUpdate()->findOrFail($pendingTx->wallet_id);

            if (!$wallet->isActive()) {
                throw new \RuntimeException("Wallet is {$wallet->status} — cannot complete deposit");
            }

            $amount        = (float) $pendingTx->amount;
            $balanceBefore = (float) $wallet->available_balance;
            $balanceAfter  = round($balanceBefore + $amount, 2);

            $pendingTx->balance_before = $balanceBefore;
            $pendingTx->balance_after  = $balanceAfter;
            $pendingTx->status         = 'successful';
            $pendingTx->metadata       = array_merge((array) ($pendingTx->metadata ?? []), $paystackData);
            $pendingTx->save();

            $wallet->available_balance = $balanceAfter;
            $wallet->save();

            self::writeAudit($wallet, $pendingTx, 'deposit_completed', ['paystack_data' => $paystackData]);

            return $pendingTx;
        });
    }

    public static function generateReference(): string
    {
        return 'WLT-' . strtoupper(Str::random(16));
    }

    private static function writeAudit(Wallet $wallet, WalletTransaction $transaction, string $action, array $context = []): void
    {
        try {
            WalletAuditLog::create([
                'wallet_id'             => $wallet->id,
                'wallet_transaction_id' => $transaction->id,
                'user_id'               => $wallet->user_id,
                'action'                => $action,
                'payload'               => [
                    'transaction_id' => $transaction->id,
                    'reference'      => $transaction->reference,
                    'amount'         => $transaction->amount,
                    'balance_before' => $transaction->balance_before,
                    'balance_after'  => $transaction->balance_after,
                    'status'         => $transaction->status,
                    'category'       => $transaction->category,
                ],
                'ip_address'  => request()?->ip(),
                'user_agent'  => request()?->header('User-Agent'),
                'performed_by' => $context['processed_by'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Wallet audit log failed', [
                'error'          => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);
        }
    }
}
