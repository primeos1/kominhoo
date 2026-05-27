<?php

namespace App\Services;

use Illuminate\Support\Str; // used in create()

class CouponService
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/coupons.json');
    }

    public function all(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->path), true);
        return is_array($data) ? $data : [];
    }

    public function find(string $code): ?array
    {
        $code = strtoupper(trim($code));
        foreach ($this->all() as $coupon) {
            if ($coupon['code'] === $code) return $coupon;
        }
        return null;
    }

    public function create(array $data): array
    {
        $coupons = $this->all();
        $coupon = [
            'id'                   => Str::uuid()->toString(),
            'code'                 => strtoupper(trim($data['code'])),
            'discount_type'        => $data['discount_type'] ?? 'percentage',
            'discount_value'       => (float) ($data['discount_value'] ?? 0),
            'free_shipping'        => (bool) ($data['free_shipping'] ?? false),
            'min_order'            => (float) ($data['min_order'] ?? 0),
            'max_uses'             => isset($data['max_uses']) && $data['max_uses'] !== '' ? (int) $data['max_uses'] : null,
            'uses_per_customer'    => (int) ($data['uses_per_customer'] ?? 1),
            'use_count'            => 0,
            'start_date'           => $data['start_date'] ?? null,
            'expiry_date'          => $data['expiry_date'] ?? null,
            'applicable_to'        => $data['applicable_to'] ?? 'all',
            'customer_restriction' => $data['customer_restriction'] ?? 'all',
            'description'          => $data['description'] ?? '',
            'active'               => true,
            'created_at'           => now()->toISOString(),
        ];
        $coupons[] = $coupon;
        $this->save($coupons);
        return $coupon;
    }

    public function update(string $id, array $data): ?array
    {
        $coupons = $this->all();
        foreach ($coupons as &$coupon) {
            if ($coupon['id'] === $id) {
                if (isset($data['code'])) $data['code'] = strtoupper(trim($data['code']));
                if (isset($data['discount_value'])) $data['discount_value'] = (float) $data['discount_value'];
                if (isset($data['min_order'])) $data['min_order'] = (float) $data['min_order'];
                if (isset($data['max_uses'])) $data['max_uses'] = $data['max_uses'] !== '' ? (int) $data['max_uses'] : null;
                if (isset($data['uses_per_customer'])) $data['uses_per_customer'] = (int) $data['uses_per_customer'];
                if (isset($data['free_shipping'])) $data['free_shipping'] = (bool) $data['free_shipping'];
                if (isset($data['active'])) $data['active'] = (bool) $data['active'];
                $coupon = array_merge($coupon, $data);
                $this->save($coupons);
                return $coupon;
            }
        }
        return null;
    }

    public function delete(string $id): bool
    {
        $coupons = $this->all();
        $filtered = array_values(array_filter($coupons, fn($c) => $c['id'] !== $id));
        if (count($filtered) === count($coupons)) return false;
        $this->save($filtered);
        return true;
    }

    /**
     * Validate a coupon code against all rules.
     *
     * $userContext keys:
     *   is_guest       bool   – guest users can't use logged-in-only codes
     *   is_new_customer bool  – true if user has no prior completed orders
     *   tier           string – 'glow' | 'radiant' | 'luxe' | null
     *   user_id        string – used to look up per-customer use count
     */
    public function validate(string $code, float $subtotal, array $userContext = []): array
    {
        // ── 1. Code exists ────────────────────────────────────────────
        $coupon = $this->find($code);
        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        // ── 2. Code is active (manually enabled by admin) ─────────────
        if (!($coupon['active'] ?? true)) {
            return ['success' => false, 'message' => 'This coupon is no longer active.'];
        }

        // ── 3. Start date (scheduled — not live yet) ──────────────────
        $today = now()->toDateString();
        if (!empty($coupon['start_date']) && $today < $coupon['start_date']) {
            return ['success' => false, 'message' => 'This coupon is not yet active.'];
        }

        // ── 4. Expiry date ─────────────────────────────────────────────
        if (!empty($coupon['expiry_date']) && $today > $coupon['expiry_date']) {
            return ['success' => false, 'message' => 'This coupon has expired.'];
        }

        // ── 5. Global usage limit (max_uses across all customers) ──────
        if (!empty($coupon['max_uses']) && ($coupon['use_count'] ?? 0) >= (int) $coupon['max_uses']) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.'];
        }

        // ── 6. Per-customer usage limit ───────────────────────────────
        $usesPerCustomer = (int) ($coupon['uses_per_customer'] ?? 1);
        $userId = $userContext['user_id'] ?? null;
        if ($usesPerCustomer > 0 && $userId) {
            $perUser = $coupon['per_user_counts'][$userId] ?? 0;
            if ($perUser >= $usesPerCustomer) {
                return [
                    'success' => false,
                    'message' => 'You have already used this coupon the maximum number of times.',
                ];
            }
        }

        // ── 7. Customer eligibility (restriction rules) ────────────────
        $restriction = $coupon['customer_restriction'] ?? 'all';
        if ($restriction !== 'all') {
            $isGuest = $userContext['is_guest'] ?? true;

            if ($restriction === 'new_only') {
                // Must be a logged-in user who has no prior completed orders
                if ($isGuest) {
                    return ['success' => false, 'message' => 'Log in to use this new-customer code.'];
                }
                $isNew = $userContext['is_new_customer'] ?? false;
                if (!$isNew) {
                    return ['success' => false, 'message' => 'This code is for new customers only.'];
                }
            } elseif (str_starts_with($restriction, 'tier:')) {
                // Tier-locked codes require login and the correct loyalty tier
                if ($isGuest) {
                    return ['success' => false, 'message' => 'Log in to use this members-only code.'];
                }
                $requiredTier = substr($restriction, 5); // 'glow' | 'radiant' | 'luxe'
                $userTier     = strtolower($userContext['tier'] ?? '');
                $tierNames    = ['glow' => 'Glow Starter', 'radiant' => 'Radiant Insider', 'luxe' => 'Luxe Luminary'];
                $tierName     = $tierNames[$requiredTier] ?? ucfirst($requiredTier);

                if (!$userTier || !str_contains($userTier, $requiredTier)) {
                    return [
                        'success' => false,
                        'message' => 'This code is exclusive to ' . $tierName . ' members.',
                    ];
                }
            }
        }

        // ── 8. Minimum order value ─────────────────────────────────────
        $minOrder = (float) ($coupon['min_order'] ?? 0);
        if ($subtotal < $minOrder) {
            return [
                'success' => false,
                'message' => 'Minimum order of ₦' . number_format($minOrder, 0, '.', ',') . ' required for this code.',
            ];
        }

        // ── All checks passed — calculate discount ─────────────────────
        $discountAmount = 0;
        $freeShipping   = (bool) ($coupon['free_shipping'] ?? false);

        switch ($coupon['discount_type']) {
            case 'percentage':
                $discountAmount = (int) round($subtotal * ($coupon['discount_value'] / 100));
                break;
            case 'fixed':
                $discountAmount = (int) min((float) $coupon['discount_value'], $subtotal);
                break;
            case 'free_shipping':
                $freeShipping = true;
                break;
        }

        $label = match ($coupon['discount_type']) {
            'percentage'    => $coupon['discount_value'] . '% off applied!',
            'fixed'         => '₦' . number_format((float) $coupon['discount_value'], 0, '.', ',') . ' off applied!',
            'free_shipping' => 'Free shipping applied!',
            default         => 'Discount applied!',
        };
        if ($freeShipping && $coupon['discount_type'] !== 'free_shipping') {
            $label = rtrim($label, '!') . ' + free shipping!';
        }

        return [
            'success' => true,
            'message' => $label,
            'data'    => [
                'discount_amount' => $discountAmount,
                'free_shipping'   => $freeShipping,
                'discount_type'   => $coupon['discount_type'],
                'coupon_code'     => strtoupper($code),
            ],
        ];
    }

    /**
     * Increment both the global use count and the per-user count.
     */
    public function incrementUseCount(string $code, ?string $userId = null): void
    {
        $coupons = $this->all();
        foreach ($coupons as &$coupon) {
            if ($coupon['code'] === strtoupper($code)) {
                $coupon['use_count'] = ($coupon['use_count'] ?? 0) + 1;
                // Track per-user usage if we have a user ID
                if ($userId) {
                    $coupon['per_user_counts']              ??= [];
                    $coupon['per_user_counts'][$userId]    ??= 0;
                    $coupon['per_user_counts'][$userId]++;
                }
                break;
            }
        }
        $this->save($coupons);
    }

    private function save(array $coupons): void
    {
        file_put_contents($this->path, json_encode(array_values($coupons), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

}
