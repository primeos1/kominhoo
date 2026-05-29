<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\LoyaltyService;
use App\Services\PromoEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'phone'         => 'nullable|string|max:20',
            'skin_type'     => 'nullable|string|max:100',
            'referral_code' => 'nullable|string|max:32',
        ]);

        $referralCode = $this->generateReferralCode($data['name']);

        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'phone'         => $data['phone'] ?? null,
            'skin_type'     => $data['skin_type'] ?? null,
            'referral_code' => $referralCode,
            'loyalty_points'=> 0,
            'tier'          => 'starter',
        ]);

        // Welcome loyalty points
        LoyaltyService::award($user, 'welcome', 100, 'Welcome bonus for joining Kominhoo Beauty');

        // Welcome wallet bonus — dispatched after user is fully created
        PromoEngineService::grantSignupBonus($user);

        // Handle referral if provided
        if (!empty($data['referral_code'])) {
            $referrer = User::where('referral_code', strtoupper(trim($data['referral_code'])))->first();
            if ($referrer && $referrer->id !== $user->id) {
                $referral = Referral::create([
                    'referrer_id'      => $referrer->id,
                    'referred_user_id' => $user->id,
                    'referral_code'    => strtoupper($data['referral_code']),
                    'status'           => 'completed',
                    'points_awarded'   => 500,
                ]);
                $user->update(['referred_by' => $referrer->id]);
                LoyaltyService::award($referrer, 'referral', 500, "{$user->name} joined using your referral code", 'referral', $referral->id);
                UserNotification::create([
                    'user_id' => $referrer->id,
                    'type'    => 'referral',
                    'title'   => "{$user->name} used your referral code! 🎉",
                    'message' => "You earned 500 loyalty points. {$user->name} is now a Kominhoo member.",
                    'data'    => ['referral_id' => $referral->id, 'points' => 500],
                ]);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => ['user' => $user->fresh(), 'token' => $token],
            'message' => 'Registration successful',
            'errors'  => [],
        ], 201);
    }

    private function generateReferralCode(string $name): string
    {
        $base = strtoupper(preg_replace('/[^a-zA-Z]/', '', explode(' ', $name)[0]));
        $base = substr($base, 0, 6) ?: 'USER';
        do {
            $code = $base . '-' . strtoupper(Str::random(4));
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => ['user' => $user, 'token' => $token],
            'message' => 'Login successful',
            'errors'  => [],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'data'    => [],
            'message' => 'Logged out successfully',
            'errors'  => [],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
            'message' => '',
            'errors'  => [],
        ]);
    }

    public function updateMe(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'birthday'      => 'nullable|date',
            'skin_type'     => 'nullable|string|max:100',
            'avatar'        => 'nullable|string|max:2048',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'state'         => 'nullable|string|max:100',
            'email_prefs'   => 'nullable|array',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'data'    => $user->fresh(),
            'message' => 'Profile updated',
            'errors'  => [],
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $user = $request->user();

        if (!$user->password || !Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
                'errors'  => ['current_password' => ['The current password you entered is incorrect.']],
                'data'    => [],
            ], 422);
        }

        if ($request->current_password === $request->password) {
            return response()->json([
                'success' => false,
                'message' => 'New password must be different from your current password.',
                'errors'  => ['password' => ['New password must be different from your current password.']],
                'data'    => [],
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
            'data'    => [],
            'errors'  => [],
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp',
        ]);

        $user = $request->user();
        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
        $path = $request->file('avatar')->storePublicly('avatars', ['disk' => $disk]);
        $url  = Storage::disk($disk)->url($path);

        $user->update(['avatar' => $url]);

        return response()->json([
            'success' => true,
            'data'    => ['avatar' => $url, 'user' => $user->fresh()],
            'message' => 'Avatar updated',
            'errors'  => [],
        ]);
    }

    public function social(Request $request)
    {
        $data = $request->validate([
            'provider'    => 'required|string|in:google,facebook',
            'provider_id' => 'required|string',
            'email'       => 'required|email',
            'name'        => 'required|string|max:255',
            'avatar'      => 'nullable|string',
        ]);

        $user = User::where('provider', $data['provider'])
                    ->where('provider_id', $data['provider_id'])
                    ->first();

        if (!$user) {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                $user->update([
                    'provider'    => $data['provider'],
                    'provider_id' => $data['provider_id'],
                    'avatar'      => $data['avatar'] ?? $user->avatar,
                ]);
            } else {
                $user = User::create([
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                    'password'      => null,
                    'provider'      => $data['provider'],
                    'provider_id'   => $data['provider_id'],
                    'avatar'        => $data['avatar'] ?? null,
                    'referral_code' => $this->generateReferralCode($data['name']),
                    'loyalty_points'=> 0,
                    'tier'          => 'starter',
                ]);
                LoyaltyService::award($user, 'welcome', 100, 'Welcome bonus for joining Kominhoo Beauty');
            }
        }

        $token = $user->createToken('social_auth')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => ['user' => $user, 'token' => $token],
            'message' => 'Social login successful',
            'errors'  => [],
        ]);
    }
}
