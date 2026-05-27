<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $adminEmail    = env('ADMIN_EMAIL', 'admin@kominhoo.com');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            session([
                'admin_authenticated' => true,
                'admin_user' => [
                    'name'  => env('ADMIN_NAME', 'Kominhoo Admin'),
                    'email' => $adminEmail,
                    'role'  => 'Super Administrator',
                ],
            ]);

            // Fetch backend Sanctum token for product management proxy calls
            try {
                $tokenResp = Http::timeout(5)->post(
                    rtrim(config('app.api_base_url'), '/') . '/auth/login',
                    [
                        'email'    => env('BACKEND_ADMIN_EMAIL', 'admin@kominhoo.com'),
                        'password' => env('BACKEND_ADMIN_PASSWORD', 'admin1234'),
                    ]
                );
                if ($tokenResp->successful() && $tokenResp->json('success')) {
                    session(['backend_admin_token' => $tokenResp->json('data.token')]);
                }
            } catch (\Exception $e) {
                // Non-critical — proxy methods will retry
            }

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Invalid admin credentials.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_authenticated', 'admin_user']);
        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
