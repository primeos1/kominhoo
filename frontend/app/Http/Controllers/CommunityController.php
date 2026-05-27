<?php

namespace App\Http\Controllers;

use App\Support\CommunityStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommunityController extends Controller
{
    public function __construct(private CommunityStore $store) {}

    // GET /community/posts
    public function getPosts(Request $request)
    {
        $settings = $this->store->getSettings();
        $posts = $this->store->getPosts('approved', true);
        $userKey = $this->userKey($request);

        // Attach per-user like/save state, hide the full arrays
        foreach ($posts as &$post) {
            $post['user_liked'] = in_array($userKey, $post['liked_by'] ?? []);
            $post['user_saved'] = in_array($userKey, $post['saved_by'] ?? []);
            unset($post['liked_by']);
            unset($post['saved_by']);
        }
        unset($post);

        // Pinned posts first, then newest
        $pinned = $settings['pinned_post_ids'] ?? [];
        usort($posts, function ($a, $b) use ($pinned) {
            $ap = in_array($a['id'], $pinned) ? 1 : 0;
            $bp = in_array($b['id'], $pinned) ? 1 : 0;
            if ($ap !== $bp) return $bp - $ap;
            return strcmp($b['submitted_at'] ?? '', $a['submitted_at'] ?? '');
        });

        return response()->json([
            'posts'              => array_values($posts),
            'featured_post_id'   => $settings['featured_post_id'] ?? null,
            'submissions_open'   => $settings['submissions_open'] ?? true,
        ]);
    }

    // POST /community/post
    public function submit(Request $request)
    {
        $settings = $this->store->getSettings();

        if (!($settings['submissions_open'] ?? true)) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Submissions are currently closed.'], 403)
                : back()->withErrors(['caption' => 'Submissions are currently closed.']);
        }

        $request->validate([
            'caption' => 'required|string|min:5|max:500',
            'type'    => 'nullable|in:photo,before_after,review,routine',
        ]);

        $sessionUser = session('user', []);
        $userEmail   = $sessionUser['email'] ?? $request->ip();
        $userName    = $sessionUser['name'] ?? $request->input('author_name', 'Anonymous');
        $av          = strtoupper(substr(preg_replace('/[^A-Za-z ]/', '', $userName), 0, 2)) ?: 'U';
        $palette     = ['#C8E634','#E8143C','#3B82F6','#F59E0B','#8B5CF6','#22C55E','#F97316','#0EA5E9'];
        $color       = $palette[abs(crc32($userEmail)) % count($palette)];
        $textColor   = in_array($color, ['#C8E634','#F59E0B']) ? '#0A0A0A' : '#fff';

        $type   = $request->input('type', 'photo');
        $status = 'approved';

        // Handle optional image upload
        $imgUrl = $request->input('img', '');
        if ($request->hasFile('image')) {
            $path   = $request->file('image')->store('community', 'public');
            $imgUrl = asset("storage/{$path}");
        }

        $tags = $request->input('tags', '');
        if (is_string($tags)) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $tags))));
        }

        // Parse routine-specific fields
        $routineType = in_array($request->input('routine_type'), ['AM','PM','Weekly'])
            ? $request->input('routine_type') : null;
        $stepsRaw = $request->input('steps');
        $steps = null;
        if ($stepsRaw) {
            $decoded = json_decode($stepsRaw, true);
            if (is_array($decoded)) $steps = array_values(array_filter($decoded));
        }

        // Parse multi-image array (sent as JSON string from JS)
        $imagesRaw = $request->input('images');
        $images = null;
        if ($imagesRaw) {
            $decoded = json_decode($imagesRaw, true);
            if (is_array($decoded) && count($decoded) > 1) {
                $images = $decoded;
            }
        }

        $post = [
            'id'          => uniqid('p_', true),
            'status'      => $status,
            'type'        => $type,
            'featured'    => false,
            'pinned'      => false,
            'user'        => [
                'name'      => $userName,
                'handle'    => '@' . strtolower(str_replace(' ', '_', $userName)),
                'av'        => $av,
                'color'     => $color,
                'textColor' => $textColor,
                'skin'      => $request->input('skin_type', 'Combination'),
                'email'     => $userEmail,
            ],
            'img'          => $imgUrl,
            'images'       => $images,
            'routine_type' => $routineType,
            'steps'        => $steps,
            'before_img'  => $request->input('before_img', ''),
            'after_img'   => $request->input('after_img', ''),
            'caption'     => $request->input('caption', ''),
            'quote'       => $request->input('quote', ''),
            'stars'       => (int) $request->input('stars', 5),
            'product'     => $request->input('product', ''),
            'tags'        => $tags,
            'products'    => (array) $request->input('products', []),
            'likes'       => 0,
            'liked_by'    => [],
            'saves'       => 0,
            'saved_by'    => [],
            'comments'    => 0,
            'comment_list' => [],
            'time'        => 'Just now',
            'submitted_at' => now()->toISOString(),
        ];

        $this->store->addPost($post);

        $message = $status === 'approved'
            ? 'Post published to the gallery!'
            : 'Post submitted for review! It will appear once approved.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'post' => $post, 'status' => $status]);
        }

        return back()->with('success', $message);
    }

    // POST /community/post/{id}/like
    public function toggleLike(Request $request, string $id)
    {
        if (!session('api_token')) {
            return response()->json(['success' => false, 'message' => 'Please sign in to like posts.'], 401);
        }

        $userName = session('user.name') ?? null;
        $result   = $this->store->addLike($id, $this->userKey($request), $userName);
        return response()->json($result);
    }

    // POST /community/post/{id}/comment
    public function addComment(Request $request, string $id)
    {
        if (!session('api_token')) {
            return response()->json(['success' => false, 'message' => 'Please sign in to comment.'], 401);
        }

        $request->validate(['text' => 'required|string|min:1|max:500']);

        $sessionUser = session('user', []);
        $userName    = $sessionUser['name'] ?? 'Guest';
        $email       = $sessionUser['email'] ?? $request->ip();
        $av          = strtoupper(substr(preg_replace('/[^A-Za-z ]/', '', $userName), 0, 2)) ?: 'U';
        $palette     = ['#C8E634','#E8143C','#3B82F6','#F59E0B','#8B5CF6','#22C55E'];
        $color       = $palette[abs(crc32($email)) % count($palette)];

        $saved = $this->store->addComment($id, [
            'av'         => $av,
            'color'      => $color,
            'name'       => $userName,
            'text'       => $request->input('text'),
            'user_email' => $email,
        ]);

        if (!$saved) {
            return response()->json(['success' => false, 'message' => 'Post not found.'], 404);
        }

        return response()->json(['success' => true, 'comment' => $saved]);
    }

    // DELETE /community/post/{id}/comment/{cid}
    public function deleteComment(Request $request, string $id, string $cid)
    {
        $userEmail = session('user.email') ?? $request->ip();
        $post      = $this->store->getPost($id);
        if (!$post) return response()->json(['success' => false], 404);

        $comment = collect($post['comment_list'] ?? [])->firstWhere('id', $cid);
        if (!$comment) return response()->json(['success' => false], 404);

        if (($comment['user_email'] ?? '') !== $userEmail) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $this->store->deleteComment($id, $cid);
        return response()->json(['success' => true]);
    }

    // DELETE /community/post/{id}  (owner only)
    public function deletePost(Request $request, string $id)
    {
        $post = $this->store->getPost($id);
        if (!$post) return response()->json(['success' => false], 404);

        $userEmail = $this->userKey($request);
        if (($post['user']['email'] ?? '') !== $userEmail) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $this->store->deletePost($id);
        return response()->json(['success' => true]);
    }

    // POST /community/post/{id}/save
    public function toggleSave(Request $request, string $id)
    {
        $result = $this->store->toggleSave($id, $this->userKey($request));
        return response()->json($result);
    }

    // POST /subscribe  (kept as-is, forwarding to backend API)
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $api      = config('app.api_base_url');
        $response = Http::post("{$api}/subscribe", $request->only('email', 'name'));

        return back()->with(
            $response->successful() ? 'success' : 'error',
            $response->successful() ? 'Subscribed successfully!' : 'Already subscribed or invalid email.'
        );
    }

    private function userKey(Request $request): string
    {
        return session('user.email') ?? $request->ip();
    }
}
