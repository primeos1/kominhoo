<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = User::query();
        if ($request->search) $query->where('name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%");
        return $this->apiResponse($query->latest()->paginate(20));
    }

    public function show($id)
    {
        return $this->apiResponse(User::with(['orders'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'skin_type' => 'nullable|string',
            'avatar' => 'nullable|string',
            'loyalty_points' => 'sometimes|integer|min:0',
            'tier' => 'sometimes|in:Bronze,Silver,Gold,Platinum',
            'role' => 'sometimes|in:customer,admin',
        ]);

        if ($request->password) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return $this->apiResponse($user, 'User updated');
    }

    public function store(Request $request)
    {
        return $this->apiResponse([], 'Use /auth/register to create users', false, 405);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return $this->apiResponse([], 'User deleted');
    }
}
