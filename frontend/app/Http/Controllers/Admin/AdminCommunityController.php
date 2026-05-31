<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\CommunityStore;
use Illuminate\Http\Request;

class AdminCommunityController extends Controller
{
    public function __construct(private CommunityStore $store) {}

    // GET /admin/community/posts?status=all|approved|pending|rejected
    public function posts(Request $request)
    {
        $status   = $request->query('status', 'all');
        $posts    = $this->store->getPosts($status, true);
        $settings = $this->store->getSettings();

        return response()->json(['posts' => $posts, 'settings' => $settings]);
    }

    // POST /admin/community/post  — admin creates a post directly (always approved)
    public function store(Request $request)
    {
        $request->validate(['caption' => 'required|string|min:3']);

        $imgUrl = $request->input('img', '');
        if ($request->hasFile('image')) {
            $path   = $request->file('image')->store('community', 'public');
            $imgUrl = asset("storage/{$path}");
        }

        $tags = $request->input('tags', '');
        if (is_string($tags)) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $tags))));
        }

        $name = $request->input('author_name', 'Kominhoo Team');
        $av   = strtoupper(substr($name, 0, 2));

        $post = [
            'id'           => uniqid('admin_', true),
            'status'       => 'approved',
            'type'         => $request->input('type', 'photo'),
            'featured'     => (bool) $request->input('featured', false),
            'pinned'       => (bool) $request->input('pinned', false),
            'user'         => [
                'name'      => $name,
                'handle'    => $request->input('author_handle', '@kominhoo'),
                'av'        => $av,
                'color'     => '#C8E634',
                'textColor' => '#0A0A0A',
                'skin'      => $request->input('skin_type', ''),
                'email'     => 'admin@kominhoo.com',
            ],
            'img'          => $imgUrl,
            'before_img'   => $request->input('before_img', ''),
            'after_img'    => $request->input('after_img', ''),
            'caption'      => $request->input('caption', ''),
            'quote'        => $request->input('quote', ''),
            'stars'        => (int) $request->input('stars', 5),
            'product'      => $request->input('product', ''),
            'tags'         => $tags,
            'products'     => (array) $request->input('products', []),
            'likes'        => 0,
            'liked_by'     => [],
            'saves'        => 0,
            'saved_by'     => [],
            'comments'     => 0,
            'comment_list' => [],
            'time'         => 'Just now',
            'submitted_at' => now()->toISOString(),
        ];

        $this->store->addPost($post);

        return response()->json(['success' => true, 'post' => $post]);
    }

    // PUT /admin/community/post/{id}
    public function update(Request $request, string $id)
    {
        $updates = $request->only(['caption', 'tags', 'products', 'status', 'type', 'img', 'quote', 'product', 'stars']);

        if (isset($updates['tags']) && is_string($updates['tags'])) {
            $updates['tags'] = array_values(array_filter(array_map('trim', explode(',', $updates['tags']))));
        }

        if ($request->hasFile('image')) {
            $path            = $request->file('image')->store('community', 'public');
            $updates['img']  = asset("storage/{$path}");
        }

        $post = $this->store->updatePost($id, $updates);

        return response()->json(['success' => (bool) $post, 'post' => $post]);
    }

    // DELETE /admin/community/post/{id}
    public function destroy(string $id)
    {
        return response()->json(['success' => $this->store->deletePost($id)]);
    }

    // POST /admin/community/post/{id}/approve
    public function approve(string $id)
    {
        $post = $this->store->updatePost($id, ['status' => 'approved']);
        return response()->json(['success' => (bool) $post]);
    }

    // POST /admin/community/post/{id}/reject
    public function reject(string $id)
    {
        $post = $this->store->updatePost($id, ['status' => 'rejected']);
        return response()->json(['success' => (bool) $post]);
    }

    // POST /admin/community/post/{id}/feature  — toggle; un-features all others
    public function feature(string $id)
    {
        $target = $this->store->getPost($id);
        if (!$target) return response()->json(['success' => false], 404);

        $alreadyFeatured = $target['featured'] ?? false;

        // Un-feature every currently featured post
        foreach ($this->store->getPosts('all', false) as $p) {
            if ($p['featured'] ?? false) {
                $this->store->updatePost($p['id'], ['featured' => false]);
            }
        }

        if ($alreadyFeatured) {
            $this->store->saveSettings(['featured_post_id' => null]);
            return response()->json(['success' => true, 'featured' => false]);
        }

        $this->store->updatePost($id, ['featured' => true]);
        $this->store->saveSettings(['featured_post_id' => $id]);

        return response()->json(['success' => true, 'featured' => true]);
    }

    // POST /admin/community/post/{id}/pin  — toggle pin
    public function pin(string $id)
    {
        $settings  = $this->store->getSettings();
        $pinned    = $settings['pinned_post_ids'] ?? [];
        $isPinned  = in_array($id, $pinned);

        if ($isPinned) {
            $pinned = array_values(array_filter($pinned, fn($p) => $p !== $id));
            $this->store->updatePost($id, ['pinned' => false]);
        } else {
            $pinned[] = $id;
            $this->store->updatePost($id, ['pinned' => true]);
        }

        $this->store->saveSettings(['pinned_post_ids' => $pinned]);

        return response()->json(['success' => true, 'pinned' => !$isPinned, 'pinned_ids' => $pinned]);
    }

    // DELETE /admin/community/post/{id}/comment/{cid}
    public function deleteComment(string $id, string $cid)
    {
        return response()->json(['success' => $this->store->deleteComment($id, $cid)]);
    }

    // GET /admin/community/activity
    public function activity()
    {
        $comments = \App\Models\CommunityComment::with('post:id,caption,type')
            ->orderByDesc('created_at')
            ->limit(150)
            ->get()
            ->map(fn($c) => [
                'type'         => 'comment',
                'id'           => $c->id,
                'user'         => $c->user_name,
                'text'         => $c->text,
                'post_id'      => $c->post_id,
                'post_caption' => $c->post->caption ?? '',
                'post_type'    => $c->post->type    ?? 'photo',
                'time'         => $c->created_at?->toISOString() ?? now()->toISOString(),
            ])->all();

        $likes = \App\Models\CommunityLike::with('post:id,caption,type')
            ->orderByDesc('created_at')
            ->limit(150)
            ->get()
            ->map(fn($l) => [
                'type'         => 'like',
                'id'           => 'like_' . $l->id,
                'user'         => $l->user_name ?? $l->user_key,
                'text'         => '',
                'post_id'      => $l->post_id,
                'post_caption' => $l->post->caption ?? '',
                'post_type'    => $l->post->type    ?? 'photo',
                'time'         => $l->created_at?->toISOString() ?? now()->toISOString(),
            ])->all();

        $activity = array_merge($comments, $likes);
        usort($activity, fn($a, $b) => strcmp($b['time'], $a['time']));

        return response()->json(['activity' => array_slice($activity, 0, 300)]);
    }

    // GET /admin/community/post/{id}/likers
    public function likers(string $id)
    {
        $likers = $this->store->getLikers($id);
        return response()->json(['likers' => $likers]);
    }

    // GET /admin/community/settings
    public function settings()
    {
        return response()->json($this->store->getSettings());
    }

    // PUT /admin/community/settings
    public function updateSettings(Request $request)
    {
        $allowed = ['submissions_open', 'moderation_mode', 'require_hashtag', 'allow_types'];
        $this->store->saveSettings($request->only($allowed));
        return response()->json(['success' => true, 'settings' => $this->store->getSettings()]);
    }
}
