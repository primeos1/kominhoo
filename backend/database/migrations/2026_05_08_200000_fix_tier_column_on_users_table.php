<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The original tier column is ENUM('Bronze','Silver','Gold','Platinum').
        // Our loyalty system uses 'starter','glow','radiant','iconic', which MySQL
        // rejects silently, keeping the old enum value (e.g. 'Silver').
        // Changing to VARCHAR lets us store any tier id without constraint.
        DB::statement("ALTER TABLE users MODIFY COLUMN tier VARCHAR(32) NOT NULL DEFAULT 'starter'");

        // Remap any legacy enum values to the closest new tier
        DB::statement("UPDATE users SET tier = 'starter' WHERE tier IN ('Bronze', '')");
        DB::statement("UPDATE users SET tier = 'glow'    WHERE tier = 'Silver'");
        DB::statement("UPDATE users SET tier = 'radiant' WHERE tier = 'Gold'");
        DB::statement("UPDATE users SET tier = 'iconic'  WHERE tier = 'Platinum'");
    }

    public function down(): void
    {
        // Restore original enum (data that doesn't fit will be lost — intentional)
        DB::statement("ALTER TABLE users MODIFY COLUMN tier ENUM('Bronze','Silver','Gold','Platinum') NOT NULL DEFAULT 'Bronze'");
    }
};
