<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('concerns')->nullable()->after('skin_types');
            $table->string('routine_step')->nullable()->after('concerns');
            $table->string('time_of_use')->nullable()->after('routine_step');
            $table->string('texture')->nullable()->after('time_of_use');
            $table->string('sensitivity')->nullable()->after('texture');
            $table->json('ingredients')->nullable()->after('sensitivity');
            $table->string('badge')->nullable()->after('ingredients');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['concerns', 'routine_step', 'time_of_use', 'texture', 'sensitivity', 'ingredients', 'badge']);
        });
    }
};
