<?php

namespace App\Http\Controllers;

use App\Services\GiftCardService;
use Illuminate\Http\Request;

class GiftCardController extends Controller
{
    public function index()
    {
        $service       = new GiftCardService();
        $denominations = array_filter($service->denominations(), fn($d) => $d['is_active'] ?? true);
        return view('pages.gift-cards', [
            'denominations' => array_values($denominations),
            'paystackKey'   => config('services.paystack.public_key'),
            'user'          => session('user'),
        ]);
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'amount'          => 'required|integer|min:1000',
            'purchaser_name'  => 'required|string|max:255',
            'purchaser_email' => 'required|email|max:255',
            'recipient_name'  => 'required|string|max:255',
            'recipient_email' => 'required|email|max:255',
            'message'         => 'nullable|string|max:500',
        ]);

        $card = (new GiftCardService())->generate($request->only([
            'amount', 'purchaser_name', 'purchaser_email',
            'recipient_name', 'recipient_email', 'message',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Gift card created! The recipient will receive it shortly.',
            'data'    => $card,
        ], 201);
    }

    public function validateCode(Request $request)
    {
        $request->validate(['code' => 'required|string|max:30']);
        $result = (new GiftCardService())->validate($request->input('code'));
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function myCards(Request $request)
    {
        $email = session('user.email') ?? '';
        if (!$email) {
            return response()->json(['sent' => [], 'received' => []]);
        }

        $all      = (new GiftCardService())->forEmail($email);
        $sent     = array_values(array_filter($all, fn($c) => strtolower($c['purchaser_email'] ?? '') === strtolower($email)));
        $received = array_values(array_filter($all, fn($c) => strtolower($c['recipient_email'] ?? '') === strtolower($email)));

        return response()->json(['sent' => $sent, 'received' => $received]);
    }
}
