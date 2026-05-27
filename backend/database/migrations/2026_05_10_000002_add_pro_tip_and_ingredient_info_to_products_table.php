<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('pro_tip')->nullable()->after('badge');
            $table->json('ingredient_info')->nullable()->after('pro_tip');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['pro_tip', 'ingredient_info']);
        });
    }
};
