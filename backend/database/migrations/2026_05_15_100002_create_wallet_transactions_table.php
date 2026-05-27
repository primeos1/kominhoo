<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->string('reference', 64)->unique();
            $table->enum('transaction_type', ['credit', 'debit', 'bonus', 'refund', 'reversal', 'withdrawal']);
            $table->enum('source', ['paystack', 'admin', 'promo_engine', 'order_payment', 'refund_system']);
            $table->enum('category', [
                'deposit',
                'purchase',
                'signup_bonus',
                'first_deposit_bonus',
                'referral_bonus',
                'admin_bonus',
                'campaign_bonus',
                'refund',
            ]);
            $table->decimal('amount', 18, 2);
            $table->decimal('balance_before', 18, 2);
            $table->decimal('balance_after', 18, 2);
            $table->enum('status', ['pending', 'processing', 'successful', 'failed', 'reversed'])->default('pending');
            $table->json('metadata')->nullable();
            $table->string('description')->nullable();
            $table->string('idempotency_key', 128)->unique()->nullable();
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->string('related_payment_reference', 128)->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();

            $table->index(['wallet_id', 'created_at']);
            $table->index('status');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
