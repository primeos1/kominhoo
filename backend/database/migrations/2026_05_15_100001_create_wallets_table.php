<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('available_balance', 18, 2)->default(0.00);
            $table->decimal('locked_balance', 18, 2)->default(0.00);
            $table->char('currency', 3)->default('NGN');
            $table->enum('status', ['active', 'suspended', 'frozen'])->default('active');
            $table->timestamps();
        });

        // Enforce non-negative balances at DB level
        DB::statement('ALTER TABLE wallets ADD CONSTRAINT chk_wallets_available_balance CHECK (available_balance >= 0)');
        DB::statement('ALTER TABLE wallets ADD CONSTRAINT chk_wallets_locked_balance CHECK (locked_balance >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
