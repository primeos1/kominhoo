<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routine_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('log_date');
            $table->json('am_steps')->nullable(); // array of step IDs checked
            $table->json('pm_steps')->nullable(); // array of step IDs checked
            $table->boolean('am_done')->default(false);
            $table->boolean('pm_done')->default(false);
            $table->integer('pts_earned')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'log_date']);
            $table->index(['user_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routine_logs');
    }
};
