<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * GET /api/v1/referrals/my
     * Auth user's referral code + list of people they referred.
     */
    public function my(Request $request)
    {
        $user = $request->user();

        // Ensure the user has a referral code
        if (!$user->referral_code) {
            $user->referral_code = $this->generateCode($user);
            $user->save();
        }

        $referrals = Referral::with('referredUser:id,name,created_at')
            ->where('referrer_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($r) {
                return [
                    'id'              => $r->id,
                    'status'          => $r->status,
                    'points_awarded'  => $r->points_awarded,
                    'created_at'      => $r->created_at,
                    'referred_name'   => $r->referredUser?->name,
                    'referred_joined' => $r->referredUser?->created_at,
                ];
            });

        $totalPoints = $referrals->sum('points_awarded');

        return response()->json([
            'success' => true,
            'data'    => [
                'referral_code'  => $user->referral_code,
                'referral_link'  => url('/ref/' . $user->referral_code),
                'total_referrals'=> $referrals->count(),
                'completed'      => $referrals->where('status', 'completed')->count(),
                'total_points'   => $totalPoints,
                'referrals'      => $referrals,
            ],
        ]);
    }

    /**
     * POST /api/v1/referrals/apply
     * Called after a new user registers with a referral code.
     * Marks the referral as completed and awards points to the referrer.
     */
    public function apply(Request $request)
    {
        $data = $request->validate([
            'referral_code' => 'required|string|max:32',
        ]);

        $code   = strtoupper(trim($data['referral_code']));
        $newUser = $request->user();

        // Find the referrer
        $referrer = User::where('referral_code', $code)->first();
        if (!$referrer) {
            return response()->json(['success' => false, 'message' => 'Invalid referral code.'], 404);
        }

        if ($referrer->id === $newUser->id) {
            return response()->json(['success' => false, 'message' => 'You cannot use your own referral code.'], 422);
        }

        // Don't allow applying twice
        if ($newUser->referred_by) {
            return response()->json(['success' => false, 'message' => 'A referral code has already been applied to your account.'], 422);
        }

        // Record referral
        $referral = Referral::create([
            'referrer_id'      => $referrer->id,
            'referred_user_id' => $newUser->id,
            'referral_code'    => $code,
            'status'           => 'completed',
            'points_awarded'   => 500,
        ]);

        $newUser->update(['referred_by' => $referrer->id]);

        // Award points to referrer
        LoyaltyService::award($referrer, 'referral', 500, 'Referral reward — ' . $newUser->name . ' joined and placed their first order', 'referral', $referral->id);

        // Notify referrer
        UserNotification::create([
            'user_id' => $referrer->id,
            'type'    => 'referral',
            'title'   => "{$newUser->name} used your referral code! 🎉",
            'message' => "You earned 500 loyalty points. {$newUser->name} is now a Kominhoo member thanks to you.",
            'data'    => ['referral_id' => $referral->id, 'points' => 500],
        ]);

        return response()->json(['success' => true, 'data' => $referral]);
    }

    /**
     * GET /api/v1/referrals (admin)
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 50), 200);
        $data = Referral::with(['referrer:id,name,email', 'referredUser:id,name,email'])
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json(['success' => true, 'data' => $data]);
    }

    private function generateCode(User $user): string
    {
        $base = strtoupper(preg_replace('/[^a-zA-Z]/', '', explode(' ', $user->name)[0]));
        $base = substr($base, 0, 6) ?: 'USER';
        do {
            $code = $base . '-' . strtoupper(Str::random(4));
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }
}
