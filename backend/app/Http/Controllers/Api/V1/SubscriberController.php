<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:subscribers',
            'name' => 'nullable|string|max:100',
        ]);

        $subscriber = Subscriber::create($data);
        return $this->apiResponse($subscriber, 'Subscribed successfully', true, 201);
    }

    public function index()
    {
        return $this->apiResponse(Subscriber::where('is_active', true)->latest()->paginate(50));
    }

    public function unsubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        Subscriber::where('email', $request->email)->update(['is_active' => false]);
        return $this->apiResponse([], 'Unsubscribed successfully');
    }
}
