<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminCommunityController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\WalletController;

// Influencer programme
Route::get('/be-an-influencer', [InfluencerController::class, 'show'])->name('influencer.show');
Route::post('/be-an-influencer', [InfluencerController::class, 'submit'])->name('influencer.submit');

// Public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/community', [PageController::class, 'community'])->name('community');
Route::get('/results', [PageController::class, 'results'])->name('results');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/shipping-policy', [PageController::class, 'shippingPolicy'])->name('shipping.policy');
Route::get('/returns-and-exchanges', [PageController::class, 'returnsPolicy'])->name('returns.policy');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/our-promise', [PageController::class, 'ourPromise'])->name('our-promise');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/loyalty-program', [PageController::class, 'loyaltyProgram'])->name('loyalty-program');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/product/{id}', [ShopController::class, 'product'])->name('product');
Route::post('/shop/product/{id}/review', [ShopController::class, 'submitReview'])->name('review.submit');

// Quiz
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
Route::post('/quiz', [QuizController::class, 'submit'])->name('quiz.submit');
Route::post('/quiz/progress', [QuizController::class, 'saveProgress'])->name('quiz.progress');

// Newsletter & community posts (public)
Route::post('/subscribe', [CommunityController::class, 'subscribe'])->name('subscribe');
Route::get('/community/posts', [CommunityController::class, 'getPosts'])->name('community.posts');
Route::post('/community/post', [CommunityController::class, 'submit'])->name('community.post');
Route::post('/community/post/{id}/like', [CommunityController::class, 'toggleLike'])->name('community.like');
Route::post('/community/post/{id}/comment', [CommunityController::class, 'addComment'])->name('community.comment');
Route::delete('/community/post/{id}/comment/{cid}', [CommunityController::class, 'deleteComment'])->name('community.comment.delete');
Route::post('/community/post/{id}/save', [CommunityController::class, 'toggleSave'])->name('community.save');
Route::delete('/community/post/{id}', [CommunityController::class, 'deletePost'])->name('community.post.delete');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/signup', [AuthController::class, 'register'])->name('register.submit');

    // Social auth
    Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public: subscription plans + loyalty tier config
Route::get('/subscription-plans', [MembershipController::class, 'subscriptionPlans'])->name('plans.public');
Route::get('/loyalty-tiers',      [MembershipController::class, 'loyaltyTiersConfig'])->name('loyalty.tiers.public');

// Gift Cards
Route::get('/gift-cards', [GiftCardController::class, 'index'])->name('gift-cards.index');
Route::post('/gift-cards/purchase', [GiftCardController::class, 'purchase'])->middleware('auth.user')->name('gift-cards.purchase');
Route::post('/checkout/gift-card', [GiftCardController::class, 'validateCode'])->name('checkout.gift-card');

// Vouchers (public — active codes for display in dashboard and shop)
Route::get('/vouchers', [CheckoutController::class, 'listVouchers'])->name('vouchers.list');

// Checkout (semi-public — shows page, order placement needs auth)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/promo', [CheckoutController::class, 'applyPromo'])->name('checkout.promo');
Route::post('/checkout/order', [CheckoutController::class, 'placeOrder'])->middleware('auth.user')->name('checkout.order');

// ── Admin ──────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin auth (no middleware — public)
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin panel
    Route::middleware('auth.admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/cms/content', [AdminController::class, 'updateContent'])->name('cms.content.update');
        Route::post('/cms/quiz', [AdminController::class, 'updateQuiz'])->name('cms.quiz.update');
        // Media upload (local storage)
        Route::post('/cms/media/upload', [AdminController::class, 'uploadMedia'])->name('cms.media.upload');
        // Product CRUD proxy → backend API
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.destroy');

        // Reviews
        Route::get('/reviews', [AdminController::class, 'listReviews'])->name('reviews.index');
        Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.destroy');

        // Orders
        Route::put('/orders/{id}', [AdminController::class, 'updateOrder'])->name('orders.update');

        // Coupons
        Route::get('/coupons', [AdminController::class, 'listCoupons'])->name('coupons.index');
        Route::post('/coupons', [AdminController::class, 'storeCoupon'])->name('coupons.store');
        Route::put('/coupons/{id}', [AdminController::class, 'updateCoupon'])->name('coupons.update');
        Route::delete('/coupons/{id}', [AdminController::class, 'deleteCoupon'])->name('coupons.destroy');

        // Gift Cards — denominations routes must come before the {id} wildcard
        Route::get('/gift-cards', [AdminController::class, 'listGiftCards'])->name('gift-cards.index');
        Route::post('/gift-cards', [AdminController::class, 'storeGiftCard'])->name('gift-cards.store');
        Route::post('/gift-cards/denominations', [AdminController::class, 'storeDenomination'])->name('gift-cards.denominations.store');
        Route::put('/gift-cards/denominations/{id}', [AdminController::class, 'updateDenomination'])->name('gift-cards.denominations.update');
        Route::put('/gift-cards/{id}', [AdminController::class, 'updateGiftCard'])->name('gift-cards.update');
        Route::delete('/gift-cards/{id}', [AdminController::class, 'deleteGiftCard'])->name('gift-cards.destroy');

        // Community Gallery CMS
        Route::get('/community/posts', [AdminCommunityController::class, 'posts'])->name('community.posts');
        Route::post('/community/post', [AdminCommunityController::class, 'store'])->name('community.store');
        Route::put('/community/post/{id}', [AdminCommunityController::class, 'update'])->name('community.update');
        Route::delete('/community/post/{id}', [AdminCommunityController::class, 'destroy'])->name('community.destroy');
        Route::post('/community/post/{id}/approve', [AdminCommunityController::class, 'approve'])->name('community.approve');
        Route::post('/community/post/{id}/reject', [AdminCommunityController::class, 'reject'])->name('community.reject');
        Route::post('/community/post/{id}/feature', [AdminCommunityController::class, 'feature'])->name('community.feature');
        Route::post('/community/post/{id}/pin', [AdminCommunityController::class, 'pin'])->name('community.pin');
        Route::delete('/community/post/{id}/comment/{cid}', [AdminCommunityController::class, 'deleteComment'])->name('community.comment.delete');
        Route::get('/community/settings', [AdminCommunityController::class, 'settings'])->name('community.settings');
        Route::put('/community/settings', [AdminCommunityController::class, 'updateSettings'])->name('community.settings.update');
        Route::get('/community/activity', [AdminCommunityController::class, 'activity'])->name('community.activity');
        Route::get('/community/post/{id}/likers', [AdminCommunityController::class, 'likers'])->name('community.likers');

        // Blog (local DB)
        Route::get('/blog/posts', [AdminBlogController::class, 'index'])->name('blog.posts.index');
        Route::get('/blog/posts/{id}', [AdminBlogController::class, 'show'])->name('blog.posts.show');
        Route::post('/blog/posts', [AdminBlogController::class, 'store'])->name('blog.posts.store');
        Route::put('/blog/posts/{id}', [AdminBlogController::class, 'update'])->name('blog.posts.update');
        Route::delete('/blog/posts/{id}', [AdminBlogController::class, 'destroy'])->name('blog.posts.destroy');

        // Routine monitoring + editing
        Route::get('/routine/stats',                         [AdminController::class, 'routineStats'])->name('routine.stats');
        Route::get('/routine/user/{userId}/logs',            [AdminController::class, 'routineUserLogs'])->name('routine.user.logs');
        Route::put('/routine/user/{userId}/log/{date}',      [AdminController::class, 'routineUpdateLog'])->name('routine.user.log.update');

        // Wallet management
        Route::get('/wallet/wallets',               [AdminController::class, 'listWallets'])->name('wallet.wallets');
        Route::get('/wallet/wallets/{id}',          [AdminController::class, 'showWallet'])->name('wallet.wallet.show');
        Route::get('/wallet/transactions',          [AdminController::class, 'listWalletTransactions'])->name('wallet.transactions');
        Route::get('/wallet/audit-logs',            [AdminController::class, 'listWalletAuditLogs'])->name('wallet.audit');
        Route::post('/wallet/bonus',                [AdminController::class, 'grantWalletBonus'])->name('wallet.bonus');
        Route::post('/wallet/campaign-bonus',       [AdminController::class, 'grantWalletCampaignBonus'])->name('wallet.campaign-bonus');
        Route::patch('/wallet/{walletId}/status',   [AdminController::class, 'updateWalletStatus'])->name('wallet.status');
        Route::get('/wallet/bonus-config',          [AdminController::class, 'getWalletBonusConfig'])->name('wallet.bonus-config');
        Route::post('/wallet/bonus-config',         [AdminController::class, 'updateWalletBonusConfig'])->name('wallet.bonus-config.update');
        Route::get('/wallet/bonus-stats',           [AdminController::class, 'getWalletBonusStats'])->name('wallet.bonus-stats');
        Route::post('/wallet/grant-signup-bonus',   [AdminController::class, 'grantMissingSignupBonuses'])->name('wallet.grant-signup-bonus');
        Route::post('/wallet/grant-zero-balance',   [AdminController::class, 'grantZeroBalanceBonus'])->name('wallet.grant-zero-balance');
        Route::post('/wallet/init-wallets',         [AdminController::class, 'initWallets'])->name('wallet.init-wallets');

        // Membership management
        Route::get('/loyalty/config',               [AdminController::class, 'getLoyaltyConfig'])->name('loyalty.config');
        Route::post('/loyalty/config',              [AdminController::class, 'updateLoyaltyConfig'])->name('loyalty.config.update');
        Route::post('/loyalty/award',               [AdminController::class, 'awardMemberPoints'])->name('loyalty.award');
        Route::get('/subscription-plans',           [AdminController::class, 'listSubscriptionPlans'])->name('subscription-plans.index');
        Route::post('/subscription-plans',          [AdminController::class, 'storeSubscriptionPlan'])->name('subscription-plans.store');
        Route::put('/subscription-plans/{id}',      [AdminController::class, 'updateSubscriptionPlan'])->name('subscription-plans.update');
        Route::delete('/subscription-plans/{id}',   [AdminController::class, 'deleteSubscriptionPlan'])->name('subscription-plans.destroy');
        Route::get('/members',                      [AdminController::class, 'listMembers'])->name('members.index');
        Route::get('/members/subscriptions',        [AdminController::class, 'listMemberSubscriptions'])->name('members.subscriptions');
        Route::post('/notifications/send',          [AdminController::class, 'sendMemberNotification'])->name('notifications.send');

        // Influencer applications
        Route::get('/influencers',                          [AdminController::class, 'listInfluencers'])->name('influencers.index');
        Route::patch('/influencers/{id}/status',            [AdminController::class, 'updateInfluencerStatus'])->name('influencers.status');
        Route::delete('/influencers/{id}',                  [AdminController::class, 'deleteInfluencer'])->name('influencers.destroy');

        // Security event monitoring
        Route::get('/security/events',              [AdminController::class, 'listSecurityEvents'])->name('security.events');
        Route::delete('/security/events',           [AdminController::class, 'clearSecurityEvents'])->name('security.events.clear');
    });
});

// Lightweight wallet balance — used by the nav chip on every page
Route::middleware('auth.user')->get('/user/wallet-balance', [WalletController::class, 'balance'])->name('user.wallet.balance');

// Protected dashboard
Route::middleware('auth.user')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/address', [DashboardController::class, 'updateAddress'])->name('profile.address');
    Route::post('/profile/email-prefs', [DashboardController::class, 'updateEmailPrefs'])->name('profile.email_prefs');
    Route::post('/profile/avatar', [DashboardController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::get('/community/posts', [DashboardController::class, 'communityPosts'])->name('community.posts');
    Route::get('/gift-cards', [GiftCardController::class, 'myCards'])->name('gift-cards');

    // Security
    Route::post('/security/password',       [DashboardController::class, 'updatePassword'])->name('security.password');
    Route::post('/security/settings',       [DashboardController::class, 'updateSecuritySettings'])->name('security.settings');
    Route::post('/security/delete-request', [DashboardController::class, 'requestAccountDeletion'])->name('security.delete-request');

    // Routine tracker
    Route::get('/routine/data',  [DashboardController::class, 'routineData'])->name('routine.data');
    Route::post('/routine/log',  [DashboardController::class, 'logRoutine'])->name('routine.log');

    // Wallet
    Route::get('/wallet',                  [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit',         [WalletController::class, 'initiateDeposit'])->name('wallet.deposit');
    Route::post('/wallet/initiate',        [WalletController::class, 'initiate'])->name('wallet.initiate');
    Route::post('/wallet/deposit/verify',  [WalletController::class, 'verifyDeposit'])->name('wallet.deposit.verify');
    Route::get('/wallet/callback',         [WalletController::class, 'callback'])->name('wallet.callback');

    // Membership API proxies
    Route::prefix('membership')->name('membership.')->group(function () {
        Route::get('/loyalty/summary',      [MembershipController::class, 'loyaltySummary'])->name('loyalty.summary');
        Route::get('/loyalty/events',       [MembershipController::class, 'loyaltyEvents'])->name('loyalty.events');
        Route::post('/loyalty/redeem',      [MembershipController::class, 'redeemPoints'])->name('loyalty.redeem');
        Route::get('/loyalty/tiers',        [MembershipController::class, 'loyaltyTiersConfig'])->name('loyalty.tiers');
        Route::get('/subscription',         [MembershipController::class, 'mySubscription'])->name('subscription.my');
        Route::get('/subscription/history', [MembershipController::class, 'subscriptionHistory'])->name('subscription.history');
        Route::post('/subscription',        [MembershipController::class, 'subscribe'])->name('subscription.subscribe');
        Route::patch('/subscription/{id}',  [MembershipController::class, 'updateSubscription'])->name('subscription.update');
        Route::get('/plans',                [MembershipController::class, 'subscriptionPlans'])->name('plans');
        Route::get('/referrals',            [MembershipController::class, 'myReferrals'])->name('referrals');
        Route::post('/referrals/apply',     [MembershipController::class, 'applyReferral'])->name('referrals.apply');
        Route::get('/notifications',        [MembershipController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [MembershipController::class, 'markNotificationRead'])->name('notifications.read');
        Route::post('/notifications/read-all',  [MembershipController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
        Route::delete('/notifications/{id}',    [MembershipController::class, 'deleteNotification'])->name('notifications.delete');
    });
});
