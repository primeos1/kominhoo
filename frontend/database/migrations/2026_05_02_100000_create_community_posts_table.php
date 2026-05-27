<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('community_posts');
        Schema::create('community_posts', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('status', 20)->default('approved')->index();
            $table->string('type', 20)->default('photo');
            $table->boolean('featured')->default(false);
            $table->boolean('pinned')->default(false);
            // Denormalised user snapshot
            $table->string('user_name');
            $table->string('user_handle');
            $table->string('user_av', 4);
            $table->string('user_color', 10);
            $table->string('user_text_color', 10)->default('#fff');
            $table->string('user_skin')->nullable();
            $table->string('user_email')->index();
            // Content (longText to accommodate base64-encoded images)
            $table->longText('img')->nullable();
            $table->longText('before_img')->nullable();
            $table->longText('after_img')->nullable();
            $table->text('caption');
            $table->text('quote')->nullable();
            $table->unsignedTinyInteger('stars')->nullable();
            $table->string('product')->nullable();
            $table->json('tags')->nullable();
            $table->json('tagged_products')->nullable();
            // Denormalised counters (kept in sync on each like/comment)
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_posts');
    }
};
