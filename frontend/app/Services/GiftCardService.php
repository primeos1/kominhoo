<?php

namespace App\Services;

use Illuminate\Support\Str;

class GiftCardService
{
    private string $cardsPath;
    private string $denominationsPath;

    public function __construct()
    {
        $this->cardsPath         = storage_path('app/gift_cards.json');
        $this->denominationsPath = storage_path('app/gift_card_denominations.json');
    }

    // ── Cards ────────────────────────────────────────────────────────

    public function all(): array
    {
        if (!file_exists($this->cardsPath)) return [];
        $data = json_decode(file_get_contents($this->cardsPath), true);
        return is_array($data) ? $data : [];
    }

    public function find(string $code): ?array
    {
        $code = strtoupper(trim($code));
        foreach ($this->all() as $card) {
            if ($card['code'] === $code) return $card;
        }
        return null;
    }

    public function findById(string $id): ?array
    {
        foreach ($this->all() as $card) {
            if ($card['id'] === $id) return $card;
        }
        return null;
    }

    public function generate(array $data): array
    {
        $cards  = $this->all();
        $amount = (int) $data['amount'];
        $card   = [
            'id'              => Str::uuid()->toString(),
            'code'            => $this->generateCode(),
            'amount'          => $amount,
            'balance'         => $amount,
            'status'          => 'active',
            'purchaser_name'  => $data['purchaser_name']  ?? '',
            'purchaser_email' => $data['purchaser_email'] ?? '',
            'recipient_name'  => $data['recipient_name']  ?? '',
            'recipient_email' => $data['recipient_email'] ?? '',
            'message'         => $data['message'] ?? '',
            'expires_at'      => now()->addYear()->toDateString(),
            'redeemed_at'     => null,
            'used_in_order'   => null,
            'created_at'      => now()->toISOString(),
        ];
        $cards[] = $card;
        $this->saveCards($cards);
        return $card;
    }

    public function update(string $id, array $data): ?array
    {
        $cards = $this->all();
        foreach ($cards as &$card) {
            if ($card['id'] === $id) {
                $card = array_merge($card, $data);
                $this->saveCards($cards);
                return $card;
            }
        }
        return null;
    }

    public function delete(string $id): bool
    {
        $cards    = $this->all();
        $filtered = array_values(array_filter($cards, fn($c) => $c['id'] !== $id));
        if (count($filtered) === count($cards)) return false;
        $this->saveCards($filtered);
        return true;
    }

    public function validate(string $code): array
    {
        $card = $this->find($code);

        if (!$card) {
            return ['success' => false, 'message' => 'Gift card code not found.'];
        }
        if ($card['status'] === 'redeemed') {
            return ['success' => false, 'message' => 'This gift card has already been fully redeemed.'];
        }
        if (!empty($card['expires_at']) && now()->toDateString() > $card['expires_at']) {
            return ['success' => false, 'message' => 'This gift card has expired.'];
        }
        if ((int) $card['balance'] <= 0) {
            return ['success' => false, 'message' => 'This gift card has no remaining balance.'];
        }

        return [
            'success' => true,
            'message' => 'Gift card applied! ₦' . number_format($card['balance'], 0, '.', ',') . ' balance available.',
            'data'    => [
                'code'    => $card['code'],
                'balance' => (int) $card['balance'],
                'amount'  => (int) $card['amount'],
            ],
        ];
    }

    public function redeem(string $code, int $amountToRedeem): bool
    {
        $cards = $this->all();
        foreach ($cards as &$card) {
            if ($card['code'] === strtoupper(trim($code))) {
                $newBalance          = max(0, (int) $card['balance'] - $amountToRedeem);
                $card['balance']     = $newBalance;
                $card['status']      = $newBalance <= 0 ? 'redeemed' : 'partially_used';
                $card['redeemed_at'] = now()->toISOString();
                $this->saveCards($cards);
                return true;
            }
        }
        return false;
    }

    public function stats(): array
    {
        $cards         = $this->all();
        $totalSold     = count($cards);
        $totalValue    = array_sum(array_column($cards, 'amount'));
        $redeemed      = count(array_filter($cards, fn($c) => $c['status'] === 'redeemed'));
        $active        = count(array_filter($cards, fn($c) => $c['status'] === 'active'));
        $partiallyUsed = count(array_filter($cards, fn($c) => $c['status'] === 'partially_used'));
        $outstanding   = $active + $partiallyUsed;
        $rate          = $totalSold > 0 ? round(($redeemed / $totalSold) * 100) : 0;

        return compact('totalSold', 'totalValue', 'redeemed', 'active', 'outstanding', 'rate');
    }

    public function denominationStats(): array
    {
        $cards = $this->all();
        $stats = [];
        foreach ($cards as $card) {
            $amount = (int) $card['amount'];
            if (!isset($stats[$amount])) {
                $stats[$amount] = ['amount' => $amount, 'sold' => 0, 'revenue' => 0];
            }
            $stats[$amount]['sold']++;
            $stats[$amount]['revenue'] += $amount;
        }
        ksort($stats);
        return array_values($stats);
    }

    public function forEmail(string $email): array
    {
        $email = strtolower(trim($email));
        return array_values(array_filter(
            $this->all(),
            fn($c) => strtolower($c['purchaser_email'] ?? '') === $email
                   || strtolower($c['recipient_email'] ?? '') === $email
        ));
    }

    // ── Denominations ────────────────────────────────────────────────

    public function denominations(): array
    {
        if (!file_exists($this->denominationsPath)) {
            return $this->defaultDenominations();
        }
        $data = json_decode(file_get_contents($this->denominationsPath), true);
        return is_array($data) ? $data : $this->defaultDenominations();
    }

    public function addDenomination(array $data): array
    {
        $denoms = $this->denominations();
        $denom  = [
            'id'          => Str::uuid()->toString(),
            'amount'      => (int) $data['amount'],
            'label'       => $data['label']       ?? '',
            'description' => $data['description'] ?? '',
            'is_popular'  => (bool) ($data['is_popular'] ?? false),
            'is_active'   => true,
            'created_at'  => now()->toISOString(),
        ];
        $denoms[] = $denom;
        usort($denoms, fn($a, $b) => $a['amount'] <=> $b['amount']);
        $this->saveDenominations($denoms);
        return $denom;
    }

    public function updateDenomination(string $id, array $data): ?array
    {
        $denoms = $this->denominations();
        foreach ($denoms as &$d) {
            if ($d['id'] === $id) {
                $d = array_merge($d, $data);
                $this->saveDenominations($denoms);
                return $d;
            }
        }
        return null;
    }

    // ── Private ──────────────────────────────────────────────────────

    private function generateCode(): string
    {
        do {
            $code = 'GIFT-KMH-' . strtoupper(Str::random(4));
        } while ($this->find($code));
        return $code;
    }

    private function saveCards(array $cards): void
    {
        file_put_contents(
            $this->cardsPath,
            json_encode(array_values($cards), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function saveDenominations(array $denoms): void
    {
        file_put_contents(
            $this->denominationsPath,
            json_encode(array_values($denoms), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function defaultDenominations(): array
    {
        $denoms = [
            ['id' => Str::uuid()->toString(), 'amount' => 5000,  'label' => 'Starter Glow',  'description' => 'Perfect for toners & essences', 'is_popular' => false, 'is_active' => true, 'created_at' => now()->toISOString()],
            ['id' => Str::uuid()->toString(), 'amount' => 10000, 'label' => 'Glow Boost',    'description' => 'Great for serums & treatments',  'is_popular' => true,  'is_active' => true, 'created_at' => now()->toISOString()],
            ['id' => Str::uuid()->toString(), 'amount' => 25000, 'label' => 'Skin Luxe',     'description' => 'Covers a full routine refresh',  'is_popular' => false, 'is_active' => true, 'created_at' => now()->toISOString()],
            ['id' => Str::uuid()->toString(), 'amount' => 50000, 'label' => 'Total Glow Up', 'description' => 'The ultimate skincare treat',    'is_popular' => false, 'is_active' => true, 'created_at' => now()->toISOString()],
        ];
        $this->saveDenominations($denoms);
        return $denoms;
    }
}
