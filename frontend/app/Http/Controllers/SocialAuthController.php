<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private string $api;
    private array $providers = ['google', 'facebook'];

    public function __construct()
    {
        $this->api = config('app.api_base_url');
    }

    public function redirect(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        if (!in_array($provider, $this->providers)) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Social login was cancelled or failed. Please try again.']);
        }

        if (!$socialUser->getEmail()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your ' . ucfirst($provider) . ' account has no email address. Please use email/password login.']);
        }

        $response = Http::post("{$this->api}/auth/social", [
            'provider'    => $provider,
            'provider_id' => $socialUser->getId(),
            'email'       => $socialUser->getEmail(),
            'name'        => $socialUser->getName() ?: $socialUser->getNickname() ?: 'User',
            'avatar'      => $socialUser->getAvatar(),
        ]);

        if (!$response->successful() || !$response->json('success')) {
            return redirect()->route('login')
                ->withErrors(['email' => $response->json('message') ?? 'Social login failed. Please try again.']);
        }

        $data = $response->json('data');
        session([
            'api_token' => $data['token'],
            'user'      => $data['user'],
        ]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Welcome to Kominhoo, ' . $data['user']['name'] . '!');
    }
}
