<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointEvent;
use App\Models\UserNotification;

class LoyaltyService
{
    /**
     * Tier thresholds — overridden by admin JSON but these are safe fallbacks.
     */
    private static array $tiers = [
        ['id' => 'starter',  'name' => 'Starter Glow',      'min' => 0,    'multiplier' => 1.0],
        ['id' => 'glow',     'name' => 'Glow Member',        'min' => 500,  'multiplier' => 1.25],
        ['id' => 'radiant',  'name' => 'Radiant Insider',    'min' => 1500, 'multiplier' => 1.5],
        ['id' => 'iconic',   'name' => 'Iconic Luminary',    'min' => 5000, 'multiplier' => 2.0],
    ];

    public static function award(
        User   $user,
        string $eventType,
        int    $points,
        string $note = '',
        string $referenceType = null,
        int    $referenceId   = null
    ): PointEvent {
        $event = PointEvent::create([
            'user_id'        => $user->id,
            'event_type'     => $eventType,
            'points'         => $points,
            'note'           => $note ?: self::defaultNote($eventType, $points),
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
        ]);

        $newTotal = max(0, (int) ($user->loyalty_points ?? 0) + $points);

        // Derive tiers from points so stale/invalid stored values are never used
        $oldTier = self::tierForPoints((int) ($user->loyalty_points ?? 0));
        $newTier = self::tierForPoints($newTotal);

        $user->loyalty_points = $newTotal;
        $user->tier           = $newTier;
        $user->save();

        if ($newTier !== $oldTier && $points > 0) {
            self::notifyTierUpgrade($user, $newTier);
        }

        return $event;
    }

    public static function tierForPoints(int $points): string
    {
        $tier = 'starter';
        foreach (self::$tiers as $t) {
            if ($points >= $t['min']) {
                $tier = $t['id'];
            }
        }
        return $tier;
    }

    public static function nextTier(string $currentTier): ?array
    {
        $found = false;
        foreach (self::$tiers as $t) {
            if ($found) return $t;
            if ($t['id'] === $currentTier) $found = true;
        }
        return null;
    }

    public static function multiplierForTier(string $tier): float
    {
        foreach (self::$tiers as $t) {
            if ($t['id'] === $tier) return $t['multiplier'];
        }
        return 1.0;
    }

    private static function notifyTierUpgrade(User $user, string $newTier): void
    {
        $tierNames = [
            'starter' => 'Starter Glow',
            'glow'    => 'Glow Member',
            'radiant' => 'Radiant Insider',
            'iconic'  => 'Iconic Luminary',
        ];
        $name = $tierNames[$newTier] ?? ucfirst($newTier);
        UserNotification::create([
            'user_id' => $user->id,
            'type'    => 'tier_upgrade',
            'title'   => "You've unlocked {$name}! 🎉",
            'message' => "Congratulations! You've reached {$name} status. Check your dashboard to claim your tier gift and explore your new benefits.",
            'data'    => ['tier' => $newTier],
        ]);
    }

    private static function defaultNote(string $type, int $points): string
    {
        $map = [
            'purchase'         => 'Points earned from purchase',
            'quiz'             => 'Skin quiz completed',
            'review'           => 'Product review submitted',
            'community_post'   => 'Community post shared',
            'before_after'     => 'Before & after transformation shared',
            'routine_post'     => 'Skincare routine shared',
            'routine_complete' => 'Daily skincare routine completed',
            'profile_complete' => 'Profile completion bonus',
            'referral'         => 'Referral reward — friend placed their first order',
            'birthday'         => 'Birthday bonus points',
            'first_order'      => 'Welcome bonus — first order placed',
            'welcome'          => 'Welcome bonus for joining Kominhoo',
            'manual'           => ($points >= 0 ? 'Points awarded by admin' : 'Points adjusted by admin'),
            'redeem'           => 'Points redeemed',
        ];
        return $map[$type] ?? 'Points transaction';
    }
}
