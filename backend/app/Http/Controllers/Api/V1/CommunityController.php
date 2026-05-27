<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = CommunityPost::query();
        $status = $request->status ?? 'approved';
        $query->where('status', $status);
        return $this->apiResponse($query->latest()->paginate($request->per_page ?? 12));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'author_name' => 'required|string|max:100',
            'caption' => 'nullable|string',
            'image' => 'nullable|string',
            'tags' => 'nullable|array',
        ]);

        $data['user_id'] = $request->user()?->id;
        $post = CommunityPost::create($data);

        if ($request->user()) {
            $type   = $data['tags']['type'] ?? 'photo';
            $pts    = match ($type) { 'before_after' => 50, 'routine' => 20, default => 30 };
            $event  = match ($type) { 'before_after' => 'before_after', 'routine' => 'routine_post', default => 'community_post' };
            LoyaltyService::award($request->user(), $event, $pts, 'Community post shared', 'community_post', $post->id);
        }

        return $this->apiResponse($post, 'Post submitted for review', true, 201);
    }

    public function show($id)
    {
        return $this->apiResponse(CommunityPost::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $post = CommunityPost::findOrFail($id);
        $data = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected',
            'caption' => 'sometimes|string',
            'tags' => 'nullable|array',
        ]);
        $post->update($data);
        return $this->apiResponse($post, 'Post updated');
    }

    public function destroy($id)
    {
        CommunityPost::findOrFail($id)->delete();
        return $this->apiResponse([], 'Post deleted');
    }
}
