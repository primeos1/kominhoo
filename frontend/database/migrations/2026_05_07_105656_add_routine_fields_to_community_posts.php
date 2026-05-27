<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->string('routine_type', 10)->nullable()->after('stars');
            $table->json('steps')->nullable()->after('routine_type');
        });
    }

    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->dropColumn(['routine_type', 'steps']);
        });
    }
};
