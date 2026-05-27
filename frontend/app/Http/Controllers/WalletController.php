<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    private string $api;

    public function __construct()
    {
        $this->api = config('app.api_base_url');
    }

    public function index()
    {
        $token  = session('api_token');
        $user   = session('user');

        $wallet       = $this->fetchSafe("{$this->api}/wallet", $token);
        $transactions = $this->fetchSafe("{$this->api}/wallet/transactions?per_page=20", $token);

        // Sidebar data — same pattern as DashboardController
        $loyaltySummary = $this->fetchSafe("{$this->api}/loyalty/summary", $token);
        $notifData      = $this->fetchSafe("{$this->api}/notifications", $token);

        return view('pages.wallet', compact(
            'user', 'wallet', 'transactions', 'loyaltySummary', 'notifData'
        ));
    }

    public function balance(): \Illuminate\Http\JsonResponse
    {
        $token = session('api_token');

        if (!$token) {
            return response()->json(['success' => false]);
        }

        try {
            $response = Http::withToken($token)
                ->timeout(5)
                ->get("{$this->api}/wallet");

            if (!$response->successful()) {
                return response()->json(['success' => false]);
            }

            $bal = $response->json('data.wallet.available_balance');

            if ($bal === null) {
                return response()->json(['success' => false]);
            }

            return response()->json(['success' => true, 'balance' => (float) $bal]);

        } catch (\Throwable) {
            return response()->json(['success' => false]);
        }
    }

    public function initiateDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $token    = session('api_token');
        $callback = url('/dashboard/wallet/callback');

        $response = Http::withToken($token)->post("{$this->api}/wallet/deposit", [
            'amount'       => $request->amount,
            'callback_url' => $callback,
        ]);

        if (!$response->successful()) {
            $message = $response->json('message') ?? 'Could not initialize payment. Please try again.';
            return back()->with('wallet_error', $message);
        }

        $data = $response->json('data');
        return redirect($data['authorization_url']);
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $token    = session('api_token');
        $callback = url(route('dashboard.wallet.callback', [], false));

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->post("{$this->api}/wallet/deposit", [
                    'amount'       => $request->amount,
                    'callback_url' => $callback,
                ]);
        } catch (\Illuminate\Http\Client\ConnectionException) {
            return response()->json([
                'success' => false,
                'message' => 'Could not reach payment server. Please try again.',
            ], 504);
        }

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Could not initialise payment. Please try again.',
            ], 422);
        }

        $data = $response->json('data');

        return response()->json([
            'success'   => true,
            'reference' => $data['reference'] ?? $data['access_code'] ?? ('WLT-' . uniqid()),
        ]);
    }

    public function verifyDeposit(Request $request)
    {
        $request->validate(['reference' => 'required|string|max:100']);

        $token = session('api_token');

        try {
            $response = Http::withToken($token)
                ->timeout(20)
                ->post("{$this->api}/wallet/deposit/verify", [
                    'reference' => $request->input('reference'),
                ]);
        } catch (\Illuminate\Http\Client\ConnectionException) {
            return response()->json([
                'success' => false,
                'message' => 'Could not reach payment server to confirm. Please refresh your wallet balance.',
            ], 504);
        }

        return response()->json($response->json(), $response->status());
    }

    public function callback(Request $request)
    {
        // The actual wallet crediting happens via webhook — this is purely a UI landing page.
        // Paystack sends reference in query string; we show a "verifying" state.
        $reference = $request->query('reference');

        return view('pages.wallet-callback', compact('reference'));
    }

    private function fetchSafe(string $url, ?string $token): array
    {
        try {
            return Http::withToken($token)->get($url)->json('data') ?? [];
        } catch (\Throwable) {
            return [];
        }
    }
}
