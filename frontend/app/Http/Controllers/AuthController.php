<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private string $api;

    public function __construct()
    {
        $this->api = config('app.api_base_url');
    }

    public function showLogin()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $response = Http::acceptJson()->post("{$this->api}/auth/login", $request->only('email', 'password'));

        if ($response->successful()) {
            $data = $response->json('data');
            session(['api_token' => $data['token'], 'user' => $data['user']]);
            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors(['email' => $response->json('message') ?? 'Login failed'])->withInput();
    }

    public function showRegister()
    {
        return view('pages.signup');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'password'  => 'required|min:8|confirmed',
            'phone'     => 'nullable|string|max:20',
            'skin_type' => 'nullable|string|max:100',
        ]);

        try {
            $response = Http::acceptJson()->post("{$this->api}/auth/register", $request->only(
                'name', 'email', 'password', 'password_confirmation', 'phone', 'skin_type'
            ));
        } catch (\Exception $e) {
            \Log::error('Register API connection error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Could not connect to server. Please try again.'])->withInput();
        }

        if ($response->successful()) {
            $data = $response->json('data');
            session(['api_token' => $data['token'], 'user' => $data['user']]);
            return redirect()->route('quiz')->with('success', 'Welcome! Take the quiz to personalise your routine.');
        }

        $errors = $response->json('errors') ?? [];
        if (empty($errors)) {
            $errors = ['email' => $response->json('message') ?? 'Registration failed. Please try again.'];
        }
        return back()->withErrors($errors)->withInput();
    }

    public function logout(Request $request)
    {
        Http::acceptJson()->withToken(session('api_token'))->post("{$this->api}/auth/logout");
        $request->session()->forget(['api_token', 'user']);
        return redirect()->route('home');
    }
}
