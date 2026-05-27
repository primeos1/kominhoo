<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * For any user who has loyalty_points > 0 but no point_events rows,
     * create a single "legacy" carry-over event so the history isn't blank.
     * Also recalculate each user's tier from their actual point total.
     */
    public function up(): void
    {
        $tiers = [
            ['id' => 'starter',  'min' => 0],
            ['id' => 'glow',     'min' => 500],
            ['id' => 'radiant',  'min' => 1500],
            ['id' => 'iconic',   'min' => 5000],
        ];

        $users = DB::table('users')->where('loyalty_points', '>', 0)->get();

        foreach ($users as $user) {
            $hasEvents = DB::table('point_events')
                ->where('user_id', $user->id)
                ->exists();

            if (! $hasEvents) {
                // Create a carry-over event so history isn't empty
                DB::table('point_events')->insert([
                    'user_id'    => $user->id,
                    'event_type' => 'manual',
                    'points'     => (int) $user->loyalty_points,
                    'note'       => 'Legacy balance carry-over from previous system',
                    'created_at' => $user->created_at ?? now(),
                    'updated_at' => now(),
                ]);
            }

            // Recalculate correct tier from actual points
            $correctTier = 'starter';
            foreach ($tiers as $t) {
                if ((int) $user->loyalty_points >= $t['min']) {
                    $correctTier = $t['id'];
                }
            }

            if ($user->tier !== $correctTier) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['tier' => $correctTier]);
            }
        }
    }

    public function down(): void
    {
        // Remove only the carry-over events we created
        DB::table('point_events')
            ->where('note', 'Legacy balance carry-over from previous system')
            ->delete();
    }
};
