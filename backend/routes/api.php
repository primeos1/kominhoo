<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\BundleController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\CommunityController;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\PromotionController;
use App\Http\Controllers\Api\V1\SubscriberController;
use App\Http\Controllers\Api\V1\GuideController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\LoyaltyController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\ReferralController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PaystackWebhookController;
use App\Http\Controllers\Api\V1\RoutineController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Controllers\Api\V1\WalletWebhookController;
use App\Http\Controllers\Api\V1\Admin\AdminWalletController;

// Paystack webhooks — outside v1 prefix, no CSRF, no auth
Route::post('webhooks/paystack',        [PaystackWebhookController::class, 'handle']);
Route::post('webhooks/paystack/wallet', [WalletWebhookController::class, 'handle']);

Route::prefix('v1')->group(function () {

    // Auth — public
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/social', [AuthController::class, 'social']);
    // Legacy aliases (keep for backward compat while frontend migrates)
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Public browse endpoints
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::get('bundles', [BundleController::class, 'index']);
    Route::get('bundles/{id}', [BundleController::class, 'show']);
    Route::get('guides', [GuideController::class, 'index']);
    Route::get('guides/{id}', [GuideController::class, 'show']);
    Route::get('reviews', [ReviewController::class, 'index']);
    Route::get('community', [CommunityController::class, 'index']);

    // Public coupon validation
    Route::post('promotions/apply', [PromotionController::class, 'applyCode']);

    // Newsletter subscribe/unsubscribe
    Route::post('subscribe', [SubscriberController::class, 'subscribe']);
    Route::post('unsubscribe', [SubscriberController::class, 'unsubscribe']);

    // Quiz — submit public; progress + history require auth
    Route::post('quiz', [QuizController::class, 'submit']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {

        // Auth (prefixed + legacy alias)
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::patch('auth/me', [AuthController::class, 'updateMe']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);
        Route::post('auth/me/avatar', [AuthController::class, 'uploadAvatar']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::patch('me', [AuthController::class, 'updateMe']);
        Route::post('me/avatar', [AuthController::class, 'uploadAvatar']);

        // Orders
        Route::apiResource('orders', OrderController::class);

        // Reviews
        Route::post('reviews', [ReviewController::class, 'store']);

        // Community posts
        Route::post('community', [CommunityController::class, 'store']);

        // Quiz
        Route::get('quiz/history', [QuizController::class, 'history']);
        Route::post('quiz/progress', [QuizController::class, 'saveProgress']);
        Route::post('quiz/{id}/claim', [QuizController::class, 'claim']);

        // Loyalty
        Route::get('loyalty/events',  [LoyaltyController::class, 'events']);
        Route::get('loyalty/summary', [LoyaltyController::class, 'summary']);
        Route::post('loyalty/redeem', [LoyaltyController::class, 'redeem']);

        // Subscriptions
        Route::get('subscriptions/my',         [SubscriptionController::class, 'my']);
        Route::get('subscriptions/my/history', [SubscriptionController::class, 'history']);
        Route::post('subscriptions',            [SubscriptionController::class, 'store']);
        Route::patch('subscriptions/{id}',      [SubscriptionController::class, 'update']);

        // Referrals
        Route::get('referrals/my',    [ReferralController::class, 'my']);
        Route::post('referrals/apply',[ReferralController::class, 'apply']);

        // Notifications
        Route::get('notifications',              [NotificationController::class, 'index']);
        Route::post('notifications/read-all',    [NotificationController::class, 'markAllRead']);
        Route::post('notifications/{id}/read',   [NotificationController::class, 'markRead']);
        Route::delete('notifications/{id}',      [NotificationController::class, 'destroy']);

        // Routine tracker
        Route::get('routine/steps',  [RoutineController::class, 'steps']);
        Route::get('routine/logs',   [RoutineController::class, 'logs']);
        Route::post('routine/log',   [RoutineController::class, 'log']);

        // Wallet
        Route::get('wallet',                  [WalletController::class, 'show']);
        Route::get('wallet/transactions',     [WalletController::class, 'transactions']);
        Route::post('wallet/deposit',         [WalletController::class, 'initiateDeposit']);
        Route::post('wallet/deposit/verify',  [WalletController::class, 'verifyDeposit']);
        Route::post('wallet/pay',             [WalletController::class, 'payWithWallet']);

        // Admin-only routes
        Route::middleware('admin')->group(function () {
            Route::apiResource('products', ProductController::class)->except(['index', 'show']);
            Route::apiResource('bundles', BundleController::class)->except(['index', 'show']);
            Route::apiResource('guides', GuideController::class)->except(['index', 'show']);
            Route::put('reviews/{id}', [ReviewController::class, 'update']);
            Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
            Route::put('community/{id}', [CommunityController::class, 'update']);
            Route::delete('community/{id}', [CommunityController::class, 'destroy']);
            Route::apiResource('promotions', PromotionController::class)->except(['validate']);
            Route::get('subscribers', [SubscriberController::class, 'index']);
            Route::apiResource('users', UserController::class)->except(['store']);
            Route::get('quiz/results', [QuizController::class, 'listResults']);

            // Admin loyalty/subscription/referral management
            Route::post('loyalty/award',         [LoyaltyController::class, 'award']);
            Route::get('subscriptions',           [SubscriptionController::class, 'index']);
            Route::get('referrals',               [ReferralController::class, 'index']);
            Route::post('notifications/send',     [NotificationController::class, 'send']);

            // Admin routine monitoring
            Route::get('routine/admin/stats',                          [RoutineController::class, 'adminStats']);
            Route::get('routine/admin/user/{userId}/logs',             [RoutineController::class, 'adminUserLogs']);
            Route::put('routine/admin/user/{userId}/log/{date}',       [RoutineController::class, 'adminUpdateLog']);

            // Admin wallet management — specific paths BEFORE wildcards
            Route::get('admin/wallets',                                [AdminWalletController::class, 'index']);
            Route::get('admin/wallets/transactions',                   [AdminWalletController::class, 'transactions']);
            Route::get('admin/wallets/audit-logs',                     [AdminWalletController::class, 'auditLogs']);
            Route::get('admin/wallets/bonus-config',                   [AdminWalletController::class, 'getBonusConfig']);
            Route::get('admin/wallets/bonus-stats',                    [AdminWalletController::class, 'bonusStats']);
            Route::post('admin/wallets/bonus',                         [AdminWalletController::class, 'grantBonus']);
            Route::post('admin/wallets/bonus-config',                  [AdminWalletController::class, 'updateBonusConfig']);
            Route::post('admin/wallets/campaign-bonus',                [AdminWalletController::class, 'grantCampaignBonusBulk']);
            Route::post('admin/wallets/grant-signup-bonus',            [AdminWalletController::class, 'grantMissingSignupBonuses']);
            Route::post('admin/wallets/grant-zero-balance-bonus',      [AdminWalletController::class, 'grantZeroBalanceBonus']);
            Route::post('admin/wallets/init-wallets',                  [AdminWalletController::class, 'initWallets']);
            // Wildcard routes last so named segments above match first
            Route::get('admin/wallets/{walletId}',                     [AdminWalletController::class, 'show']);
            Route::patch('admin/wallets/{walletId}/status',            [AdminWalletController::class, 'updateStatus']);
        });
    });
});
