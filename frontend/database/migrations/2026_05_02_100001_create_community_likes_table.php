<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_likes', function (Blueprint $table) {
            $table->id();
            $table->string('post_id', 40);
            $table->string('user_key');          // email or IP
            $table->string('user_name')->nullable(); // display name for admin activity
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['post_id', 'user_key']);
            $table->foreign('post_id')
                  ->references('id')->on('community_posts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_likes');
    }
};
