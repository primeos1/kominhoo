<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('x-paystack-signature', '');

        $paystack = new PaystackService();

        if (!$paystack->validateWebhookSignature($payload, $signature)) {
            Log::warning('Paystack webhook: invalid signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $event = $request->input('event');
        $data  = $request->input('data', []);

        if ($event === 'charge.success') {
            $this->handleChargeSuccess($data);
        }

        return response()->json(['message' => 'ok'], 200);
    }

    private function handleChargeSuccess(array $data): void
    {
        $reference = $data['reference'] ?? null;
        if (!$reference) return;

        $order = Order::where('payment_reference', $reference)->first();
        if (!$order) {
            Log::info('Paystack webhook: order not found for reference', ['ref' => $reference]);
            return;
        }

        if ($order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
            Log::info('Paystack webhook: marked order paid', ['order' => $order->order_number]);
        }
    }
}
