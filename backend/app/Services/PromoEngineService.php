<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;

class PromoEngineService
{
    private static function cfg(): array
    {
        $path = storage_path('app/cms/wallet_bonus_config.json');
        $file = $path && file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        return array_merge([
            'signup_bonus'            => 500.00,
            'first_deposit_bonus_pct' => 10,
            'first_deposit_bonus_cap' => 2000.00,
            'referral_bonus'          => 300.00,
            'zero_balance_bonus'      => 200.00,
        ], is_array($file) ? $file : []);
    }

    public static function grantSignupBonus(User $user): void
    {
        try {
            $wallet = WalletService::getOrCreate($user);

            $idempotencyKey = "signup_bonus_{$user->id}";
            if (WalletTransaction::where('idempotency_key', $idempotencyKey)->exists()) {
                return;
            }

            $amount = (float) self::cfg()['signup_bonus'];

            WalletService::credit(
                $wallet,
                $amount,
                'bonus',
                'promo_engine',
                'signup_bonus',
                'Welcome bonus for joining Kominhoo Beauty',
                ['idempotency_key' => $idempotencyKey]
            );

            UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'wallet_credit',
                'title'   => 'Welcome Bonus Added!',
                'message' => '₦' . number_format($amount, 2) . ' has been added to your wallet as a welcome gift. Shop your first order today!',
                'data'    => ['amount' => $amount, 'category' => 'signup_bonus'],
            ]);
        } catch (\Throwable $e) {
            Log::error('PromoEngine: signup bonus failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
    }

    public static function grantFirstDepositBonus(User $user, float $depositAmount): void
    {
        try {
            $wallet = WalletService::getOrCreate($user);

            $idempotencyKey = "first_deposit_bonus_{$user->id}";
            if (WalletTransaction::where('idempotency_key', $idempotencyKey)->exists()) {
                return;
            }

            $cfg   = self::cfg();
            $bonus = round(min($depositAmount * ((float) $cfg['first_deposit_bonus_pct'] / 100), (float) $cfg['first_deposit_bonus_cap']), 2);
            if ($bonus <= 0) {
                return;
            }

            WalletService::credit(
                $wallet,
                $bonus,
                'bonus',
                'promo_engine',
                'first_deposit_bonus',
                $cfg['first_deposit_bonus_pct'] . '% first deposit bonus (max ₦' . number_format($cfg['first_deposit_bonus_cap'], 0) . ')',
                ['idempotency_key' => $idempotencyKey]
            );

            UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'wallet_credit',
                'title'   => 'First Deposit Bonus!',
                'message' => 'You earned ₦' . number_format($bonus, 2) . ' bonus on your first deposit!',
                'data'    => ['amount' => $bonus, 'category' => 'first_deposit_bonus'],
            ]);
        } catch (\Throwable $e) {
            Log::error('PromoEngine: first deposit bonus failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }
    }

    public static function grantReferralBonus(User $referrer, User $newUser): void
    {
        try {
            $wallet = WalletService::getOrCreate($referrer);

            $idempotencyKey = "referral_bonus_{$referrer->id}_{$newUser->id}";
            if (WalletTransaction::where('idempotency_key', $idempotencyKey)->exists()) {
                return;
            }

            $amount = (float) self::cfg()['referral_bonus'];

            WalletService::credit(
                $wallet,
                $amount,
                'bonus',
                'promo_engine',
                'referral_bonus',
                "Referral bonus — {$newUser->name} joined using your code",
                [
                    'idempotency_key' => $idempotencyKey,
                    'metadata'        => [
                        'referred_user_id'   => $newUser->id,
                        'referred_user_name' => $newUser->name,
                    ],
                ]
            );

            UserNotification::create([
                'user_id' => $referrer->id,
                'type'    => 'wallet_credit',
                'title'   => 'Referral Bonus!',
                'message' => "Your friend {$newUser->name} joined. You've earned ₦" . number_format($amount, 2) . ' in your wallet!',
                'data'    => ['amount' => $amount, 'category' => 'referral_bonus'],
            ]);
        } catch (\Throwable $e) {
            Log::error('PromoEngine: referral bonus failed', ['referrer_id' => $referrer->id, 'error' => $e->getMessage()]);
        }
    }

    public static function grantCampaignBonus(User $user, float $amount, string $campaignName, string $campaignId): void
    {
        try {
            $wallet         = WalletService::getOrCreate($user);
            $idempotencyKey = "campaign_{$campaignId}_{$user->id}";

            if (WalletTransaction::where('idempotency_key', $idempotencyKey)->exists()) {
                return;
            }

            WalletService::credit(
                $wallet,
                $amount,
                'bonus',
                'promo_engine',
                'campaign_bonus',
                "Campaign bonus: {$campaignName}",
                [
                    'idempotency_key' => $idempotencyKey,
                    'metadata'        => ['campaign_id' => $campaignId, 'campaign_name' => $campaignName],
                ]
            );

            UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'wallet_credit',
                'title'   => 'Bonus Credit!',
                'message' => "You've received ₦" . number_format($amount, 2) . " from the '{$campaignName}' promotion!",
                'data'    => ['amount' => $amount, 'category' => 'campaign_bonus'],
            ]);
        } catch (\Throwable $e) {
            Log::error('PromoEngine: campaign bonus failed', ['user_id' => $user->id, 'campaign_id' => $campaignId, 'error' => $e->getMessage()]);
        }
    }
}
