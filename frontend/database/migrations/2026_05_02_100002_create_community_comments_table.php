<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_comments', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('post_id', 40)->index();
            $table->string('user_av', 4);
            $table->string('user_color', 10);
            $table->string('user_name');
            $table->string('user_email');
            $table->text('text');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('post_id')
                  ->references('id')->on('community_posts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_comments');
    }
};
