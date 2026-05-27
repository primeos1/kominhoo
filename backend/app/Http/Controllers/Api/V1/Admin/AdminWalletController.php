<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Wallet;
use App\Models\WalletAuditLog;
use App\Models\WalletTransaction;
use App\Services\PromoEngineService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminWalletController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    /** List ALL users with their wallet data — every registered user appears even with no wallet yet. */
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 20), 100);
        $search  = (string) $request->input('search', '');
        $status  = (string) $request->input('status', '');

        try {
            // Build from users with a LEFT JOIN so every user always appears.
            // Use DB::raw bindings for string literals to avoid MySQL ANSI-mode issues.
            $query = User::select('users.id', 'users.name', 'users.email', 'users.tier')
                ->selectRaw(
                    'COALESCE(wallets.id, 0)                   AS wallet_id,' .
                    'COALESCE(wallets.available_balance, 0)    AS available_balance,' .
                    'COALESCE(wallets.locked_balance, 0)       AS locked_balance,' .
                    'COALESCE(wallets.currency, ?)              AS currency,' .
                    'COALESCE(wallets.status, ?)                AS wallet_status,' .
                    'wallets.updated_at                        AS wallet_updated_at',
                    ['NGN', 'none']
                )
                ->leftJoin('wallets', 'wallets.user_id', '=', 'users.id')
                ->when($search !== '', fn ($q) =>
                    $q->where(fn ($s) =>
                        $s->where('users.name',  'like', '%' . $search . '%')
                          ->orWhere('users.email', 'like', '%' . $search . '%')
                    )
                )
                ->when($status !== '' && $status !== 'none', fn ($q) =>
                    $q->where('wallets.status', $status)
                )
                ->orderByRaw('(wallets.id IS NULL) ASC, wallets.available_balance DESC')
                ->orderBy('users.name');

            $paginator = $query->paginate($perPage);

            // Reshape into the wallet-centric structure the admin frontend expects
            $paginator->getCollection()->transform(function ($row) {
                $walletId = (int) $row->wallet_id;
                return [
                    'id'                => $walletId > 0 ? $walletId : null,
                    'user_id'           => $row->id,
                    'available_balance' => (float) $row->available_balance,
                    'locked_balance'    => (float) $row->locked_balance,
                    'currency'          => $row->currency ?: 'NGN',
                    'status'            => ($row->wallet_status === 'none' || !$walletId)
                                            ? 'no_wallet'
                                            : $row->wallet_status,
                    'updated_at'        => $row->wallet_updated_at,
                    'user'              => [
                        'id'    => $row->id,
                        'name'  => $row->name,
                        'email' => $row->email,
                        'tier'  => $row->tier ?? 'starter',
                    ],
                ];
            });

            return $this->apiResponse($paginator);
        } catch (\Throwable $e) {
            return $this->apiResponse(
                null,
                'Wallet query failed: ' . $e->getMessage() .
                ' — run: php artisan migrate (backend)',
                false,
                500
            );
        }
    }

    /** Full transaction ledger across all users. */
    public function transactions(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 20), 100);

        $transactions = WalletTransaction::with('wallet.user:id,name,email')
            ->when($request->filled('wallet_id'), fn($q) => $q->where('wallet_id', $request->wallet_id))
            ->when($request->filled('user_id'),   fn($q) => $q->whereHas('wallet', fn($wq) => $wq->where('user_id', $request->user_id)))
            ->when($request->filled('status'),    fn($q) => $q->where('status', $request->status))
            ->when($request->filled('category'),  fn($q) => $q->where('category', $request->category))
            ->when($request->filled('source'),    fn($q) => $q->where('source', $request->source))
            ->when($request->filled('search'),    fn($q) => $q->whereHas('wallet.user', fn($uq) =>
                $uq->where('name', 'like', '%' . $request->search . '%')
                   ->orWhere('email', 'like', '%' . $request->search . '%')
            ))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'),   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->apiResponse($transactions);
    }

    /** Audit log viewer. */
    public function auditLogs(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 20), 100);

        $logs = WalletAuditLog::with('wallet.user:id,name,email', 'transaction')
            ->when($request->filled('wallet_id'), fn($q) => $q->where('wallet_id', $request->wallet_id))
            ->when($request->filled('user_id'),   fn($q) => $q->where('user_id', $request->user_id))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->apiResponse($logs);
    }

    /** Grant a bonus credit to a single user. */
    public function grantBonus(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'amount'      => 'required|numeric|min:1|max:500000',
            'description' => 'required|string|max:500',
            'category'    => 'nullable|in:admin_bonus,campaign_bonus',
        ]);

        $user     = User::findOrFail($request->user_id);
        $wallet   = WalletService::getOrCreate($user);
        $admin    = $request->user();
        $category = $request->input('category', 'admin_bonus');

        try {
            $transaction = WalletService::credit(
                $wallet,
                (float) $request->amount,
                'bonus',
                'admin',
                $category,
                $request->description,
                ['processed_by' => $admin->id]
            );

            UserNotification::create([
                'user_id' => $user->id,
                'type'    => 'wallet_credit',
                'title'   => 'Bonus Added to Your Wallet!',
                'message' => '₦' . number_format($request->amount, 2) . ' has been added to your wallet. ' . $request->description,
                'data'    => ['amount' => $request->amount, 'category' => $category, 'granted_by_admin' => $admin->id],
            ]);

            return $this->apiResponse(['transaction' => $transaction], 'Bonus granted successfully');
        } catch (\Throwable $e) {
            return $this->apiResponse(null, $e->getMessage(), false, 422);
        }
    }

    /** Bulk campaign bonus — distribute to many users with idempotency. */
    public function grantCampaignBonusBulk(Request $request)
    {
        $request->validate([
            'user_ids'      => 'required|array|min:1|max:500',
            'user_ids.*'    => 'required|exists:users,id',
            'amount'        => 'required|numeric|min:1|max:500000',
            'campaign_name' => 'required|string|max:255',
            'campaign_id'   => 'required|string|max:100',
        ]);

        $users   = User::whereIn('id', $request->user_ids)->get();
        $granted = 0;
        $failed  = 0;

        foreach ($users as $user) {
            try {
                PromoEngineService::grantCampaignBonus(
                    $user,
                    (float) $request->amount,
                    $request->campaign_name,
                    $request->campaign_id
                );
                $granted++;
            } catch (\Throwable) {
                $failed++;
            }
        }

        return $this->apiResponse(
            ['granted' => $granted, 'failed' => $failed],
            "Campaign bonus distributed to {$granted} users" . ($failed ? " ({$failed} failed)" : '')
        );
    }

    /** Single wallet detail. */
    public function show(int $walletId)
    {
        $wallet = Wallet::with('user:id,name,email,tier')->findOrFail($walletId);
        return $this->apiResponse(['wallet' => $wallet]);
    }

    /** Get current bonus rule configuration. */
    public function getBonusConfig()
    {
        $path     = storage_path('app/cms/wallet_bonus_config.json');
        $saved    = $path && file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        $defaults = [
            'signup_bonus'            => 500,
            'first_deposit_bonus_pct' => 10,
            'first_deposit_bonus_cap' => 2000,
            'referral_bonus'          => 300,
            'zero_balance_bonus'      => 200,
        ];
        return $this->apiResponse(array_merge($defaults, is_array($saved) ? $saved : []));
    }

    /** Save bonus rule configuration. */
    public function updateBonusConfig(Request $request)
    {
        $data = $request->validate([
            'signup_bonus'            => 'required|numeric|min:0|max:50000',
            'first_deposit_bonus_pct' => 'required|numeric|min:0|max:100',
            'first_deposit_bonus_cap' => 'required|numeric|min:0|max:100000',
            'referral_bonus'          => 'required|numeric|min:0|max:50000',
            'zero_balance_bonus'      => 'required|numeric|min:0|max:50000',
        ]);

        $dir = storage_path('app/cms');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        file_put_contents("{$dir}/wallet_bonus_config.json", json_encode($data, JSON_PRETTY_PRINT));

        return $this->apiResponse($data, 'Bonus configuration saved.');
    }

    /** Grant signup bonus to every user who hasn't received one yet. */
    public function grantMissingSignupBonuses()
    {
        $alreadyGranted = WalletTransaction::where('category', 'signup_bonus')
            ->pluck('wallet_id');

        $walletUserIds = Wallet::whereIn('id', $alreadyGranted)->pluck('user_id');

        $users   = User::whereNotIn('id', $walletUserIds)->get();
        $granted = 0;
        $failed  = 0;

        foreach ($users as $user) {
            try {
                PromoEngineService::grantSignupBonus($user);
                $granted++;
            } catch (\Throwable) {
                $failed++;
            }
        }

        return $this->apiResponse(
            ['granted' => $granted, 'failed' => $failed, 'eligible' => $users->count()],
            "Signup bonus dispatched to {$granted} user(s)."
        );
    }

    /** Grant a re-engagement bonus to all users with a zero or never-funded wallet. */
    public function grantZeroBalanceBonus(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:1|max:50000',
            'campaign_id' => 'required|string|max:100',
            'description' => 'required|string|max:255',
        ]);

        $wallets = Wallet::where('available_balance', '<=', 0)
            ->where('status', 'active')
            ->with('user')
            ->get();

        $granted = 0;
        $failed  = 0;

        foreach ($wallets as $wallet) {
            if (!$wallet->user) continue;
            try {
                PromoEngineService::grantCampaignBonus(
                    $wallet->user,
                    (float) $request->amount,
                    $request->description,
                    $request->campaign_id
                );
                $granted++;
            } catch (\Throwable) {
                $failed++;
            }
        }

        return $this->apiResponse(
            ['granted' => $granted, 'failed' => $failed, 'eligible' => $wallets->count()],
            "Re-engagement bonus sent to {$granted} user(s)."
        );
    }

    /** Count users eligible for each targeted bonus action. */
    public function bonusStats()
    {
        try {
            $alreadyGranted     = WalletTransaction::where('category', 'signup_bonus')->pluck('wallet_id');
            $walletUserIds      = Wallet::whereIn('id', $alreadyGranted)->pluck('user_id');
            $missingSignup      = User::whereNotIn('id', $walletUserIds)->count();
            $zeroBalance        = Wallet::where('available_balance', '<=', 0)->where('status', 'active')->count();
            $totalUsers         = User::count();
            $totalWallets       = Wallet::count();
            $usersWithoutWallet = $totalUsers - $totalWallets;

            return $this->apiResponse([
                'missing_signup_bonus'  => $missingSignup,
                'zero_balance_wallets'  => $zeroBalance,
                'total_users'           => $totalUsers,
                'total_wallets'         => $totalWallets,
                'users_without_wallet'  => max(0, $usersWithoutWallet),
            ]);
        } catch (\Throwable $e) {
            return $this->apiResponse(
                ['missing_signup_bonus' => 0, 'zero_balance_wallets' => 0,
                 'total_users' => 0, 'total_wallets' => 0, 'users_without_wallet' => 0],
                'Stats unavailable: ' . $e->getMessage() . ' — run: php artisan migrate (backend)'
            );
        }
    }

    /** Ensure every registered user has a wallet (idempotent — safe to run repeatedly). */
    public function initWallets()
    {
        try {
            $usersWithoutWallet = User::whereNotExists(function ($q) {
                $q->select(DB::raw(1))->from('wallets')->whereColumn('wallets.user_id', 'users.id');
            })->get();

            $created = 0;
            $failed  = 0;

            foreach ($usersWithoutWallet as $user) {
                try {
                    WalletService::getOrCreate($user);
                    $created++;
                } catch (\Throwable $e) {
                    Log::warning("AdminWalletController::initWallets failed for user {$user->id}: " . $e->getMessage());
                    $failed++;
                }
            }

            return $this->apiResponse(
                ['created' => $created, 'failed' => $failed, 'eligible' => $usersWithoutWallet->count()],
                "Wallets initialised for {$created} user(s)."
            );
        } catch (\Throwable $e) {
            return $this->apiResponse(
                null,
                'Init wallets failed: ' . $e->getMessage() .
                ' — run: php artisan migrate (backend)',
                false,
                500
            );
        }
    }

    /** Update wallet status (active / suspended / frozen). */
    public function updateStatus(Request $request, int $walletId)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,frozen',
        ]);

        $wallet = Wallet::findOrFail($walletId);
        $old    = $wallet->status;
        $wallet->update(['status' => $request->status]);

        WalletAuditLog::create([
            'wallet_id'    => $wallet->id,
            'user_id'      => $wallet->user_id,
            'action'       => 'status_change',
            'payload'      => ['from' => $old, 'to' => $request->status, 'by_admin' => $request->user()->id],
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->header('User-Agent'),
            'performed_by' => $request->user()->id,
        ]);

        return $this->apiResponse(['wallet' => $wallet->fresh()], "Wallet status changed to {$request->status}");
    }
}
