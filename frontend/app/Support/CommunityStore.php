<?php

namespace App\Support;

use App\Models\CommunityComment;
use App\Models\CommunityLike;
use App\Models\CommunityPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommunityStore
{
    private const SETTINGS_PATH = 'cms/community_settings.json';

    // ── Settings (still JSON — just a handful of config flags) ───────────

    public function getSettings(): array
    {
        if (!Storage::disk('local')->exists(self::SETTINGS_PATH)) {
            return $this->defaultSettings();
        }
        return array_merge(
            $this->defaultSettings(),
            json_decode(Storage::disk('local')->get(self::SETTINGS_PATH), true) ?: []
        );
    }

    public function saveSettings(array $updates): void
    {
        $current = $this->getSettings();
        Storage::disk('local')->put(
            self::SETTINGS_PATH,
            json_encode(array_merge($current, $updates), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    // ── Posts ────────────────────────────────────────────────────────────

    public function getPosts(string $status = 'approved', bool $withComments = false): array
    {
        try {
            $this->ensureSeeded();

            $query = CommunityPost::query();
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            $posts = $query->orderByDesc('created_at')->get();

            if ($withComments) {
                $posts->load('commentRows');
            }

            return $posts->map(fn($p) => $this->postToArray($p, false, $withComments))->all();
        } catch (\Throwable $e) {
            // DB not available (migration not run yet) — return empty
            return [];
        }
    }

    public function getPost(string $id): ?array
    {
        try {
            $post = CommunityPost::with('commentRows')->find($id);
            return $post ? $this->postToArray($post, true, true) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function addPost(array $data): array
    {
        try { $this->ensureSeeded(); } catch (\Throwable $_) {}

        $post = CommunityPost::create([
            'id'              => $data['id'],
            'status'          => $data['status']          ?? 'approved',
            'type'            => $data['type']             ?? 'photo',
            'featured'        => $data['featured']         ?? false,
            'pinned'          => $data['pinned']           ?? false,
            'user_name'       => $data['user']['name']     ?? '',
            'user_handle'     => $data['user']['handle']   ?? '',
            'user_av'         => $data['user']['av']       ?? 'U',
            'user_color'      => $data['user']['color']    ?? '#C8E634',
            'user_text_color' => $data['user']['textColor'] ?? '#0A0A0A',
            'user_skin'       => $data['user']['skin']     ?? null,
            'user_email'      => $data['user']['email']    ?? '',
            'img'             => $data['img']              ?? '',
            'images'          => $data['images']           ?? null,
            'before_img'      => $data['before_img']       ?? '',
            'after_img'       => $data['after_img']        ?? '',
            'routine_type'    => $data['routine_type']     ?? null,
            'steps'           => $data['steps']            ?? null,
            'caption'         => $data['caption']          ?? '',
            'quote'           => $data['quote']            ?? '',
            'stars'           => $data['stars']            ?? null,
            'product'         => $data['product']          ?? '',
            'tags'            => $data['tags']             ?? [],
            'tagged_products' => $data['products']         ?? [],
            'likes_count'     => 0,
            'comments_count'  => 0,
        ]);

        return $this->postToArray($post->fresh(), false, false);
    }

    public function updatePost(string $id, array $updates): ?array
    {
        $post = CommunityPost::find($id);
        if (!$post) return null;

        $mapped = [];
        if (isset($updates['status']))    $mapped['status']   = $updates['status'];
        if (isset($updates['featured']))  $mapped['featured'] = $updates['featured'];
        if (isset($updates['pinned']))    $mapped['pinned']   = $updates['pinned'];
        if (isset($updates['caption']))   $mapped['caption']  = $updates['caption'];
        if (isset($updates['quote']))     $mapped['quote']    = $updates['quote'];
        if (isset($updates['img']))       $mapped['img']      = $updates['img'];
        if (isset($updates['stars']))     $mapped['stars']    = $updates['stars'];
        if (isset($updates['product']))   $mapped['product']  = $updates['product'];
        if (isset($updates['type']))      $mapped['type']     = $updates['type'];
        if (isset($updates['tags']))      $mapped['tags']     = $updates['tags'];
        if (isset($updates['products']))  $mapped['tagged_products'] = $updates['products'];

        $post->update($mapped);
        return $this->postToArray($post->fresh()->load('commentRows'), false, true);
    }

    public function deletePost(string $id): bool
    {
        return CommunityPost::destroy($id) > 0;
    }

    // ── Likes ────────────────────────────────────────────────────────────

    public function addLike(string $postId, string $userKey, ?string $userName = null): array
    {
        $post = CommunityPost::find($postId);
        if (!$post) return ['success' => false, 'error' => 'Post not found'];

        $already = CommunityLike::where('post_id', $postId)->where('user_key', $userKey)->exists();
        if ($already) {
            return ['success' => true, 'liked' => true, 'likes' => $post->likes_count];
        }

        CommunityLike::create([
            'post_id'   => $postId,
            'user_key'  => $userKey,
            'user_name' => $userName,
        ]);
        $post->increment('likes_count');
        $post->refresh();
        return ['success' => true, 'liked' => true, 'likes' => $post->likes_count];
    }

    public function getLikers(string $postId): array
    {
        return CommunityLike::where('post_id', $postId)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($l) => [
                'user_key'  => $l->user_key,
                'user_name' => $l->user_name ?? $l->user_key,
                'created_at' => $l->created_at?->toISOString(),
            ])
            ->all();
    }

    // ── Comments ─────────────────────────────────────────────────────────

    public function addComment(string $postId, array $comment): ?array
    {
        $post = CommunityPost::find($postId);
        if (!$post) return null;

        $id = uniqid('c_', true);
        CommunityComment::create([
            'id'         => $id,
            'post_id'    => $postId,
            'user_av'    => $comment['av']         ?? 'U',
            'user_color' => $comment['color']      ?? '#C8E634',
            'user_name'  => $comment['name']       ?? 'Guest',
            'user_email' => $comment['user_email'] ?? '',
            'text'       => $comment['text']       ?? '',
        ]);

        $post->increment('comments_count');

        $saved = CommunityComment::find($id);
        return [
            'id'         => $saved->id,
            'av'         => $saved->user_av,
            'color'      => $saved->user_color,
            'name'       => $saved->user_name,
            'text'       => $saved->text,
            'user_email' => $saved->user_email,
            'time'       => 'Just now',
            'created_at' => $saved->created_at?->toISOString() ?? now()->toISOString(),
        ];
    }

    public function deleteComment(string $postId, string $commentId): bool
    {
        $deleted = CommunityComment::where('id', $commentId)->where('post_id', $postId)->delete();
        if ($deleted) {
            CommunityPost::where('id', $postId)->decrement('comments_count');
            // Clamp to actual count
            $actual = CommunityComment::where('post_id', $postId)->count();
            CommunityPost::where('id', $postId)->update(['comments_count' => $actual]);
        }
        return $deleted > 0;
    }

    // ── Saves (lightweight — no DB table, returns no-ops) ────────────────

    public function toggleSave(string $postId, string $userKey): array
    {
        // Saves are not persisted in the DB. The frontend tracks save state
        // in memory only. This route remains for API compatibility.
        return ['saved' => true];
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function postToArray(CommunityPost $post, bool $withLikedBy = false, bool $withComments = false): array
    {
        $arr = [
            'id'           => $post->id,
            'status'       => $post->status,
            'type'         => $post->type,
            'featured'     => (bool) $post->featured,
            'pinned'       => (bool) $post->pinned,
            'user'         => [
                'name'      => $post->user_name,
                'handle'    => $post->user_handle,
                'av'        => $post->user_av,
                'color'     => $post->user_color,
                'textColor' => $post->user_text_color,
                'skin'      => $post->user_skin ?? '',
                'email'     => $post->user_email,
            ],
            'img'          => $post->img          ?? '',
            'images'       => $post->images       ?? null,
            'before_img'   => $post->before_img   ?? '',
            'after_img'    => $post->after_img    ?? '',
            'routine_type' => $post->routine_type ?? null,
            'steps'        => $post->steps        ?? null,
            'caption'      => $post->caption      ?? '',
            'quote'        => $post->quote        ?? '',
            'stars'        => $post->stars        ?? 0,
            'product'      => $post->product      ?? '',
            'tags'         => $post->tags         ?? [],
            'products'     => $post->tagged_products ?? [],
            'likes'        => $post->likes_count,
            'comments'     => $post->comments_count,
            'liked_by'     => [],
            'saved_by'     => [],
            'saves'        => 0,
            'time'         => $this->relativeTime($post->created_at?->toISOString()),
            'submitted_at' => $post->created_at?->toISOString() ?? '',
        ];

        if ($withLikedBy) {
            $arr['liked_by'] = CommunityLike::where('post_id', $post->id)
                ->pluck('user_key')->toArray();
        }

        if ($withComments) {
            $comments = $post->relationLoaded('commentRows')
                ? $post->commentRows
                : $post->commentRows()->get();

            $arr['comment_list'] = $comments->map(fn($c) => [
                'id'         => $c->id,
                'av'         => $c->user_av,
                'color'      => $c->user_color,
                'name'       => $c->user_name,
                'text'       => $c->text,
                'user_email' => $c->user_email,
                'time'       => $this->relativeTime($c->created_at?->toISOString()),
                'created_at' => $c->created_at?->toISOString() ?? '',
            ])->toArray();
        } else {
            $arr['comment_list'] = [];
        }

        return $arr;
    }

    private function relativeTime(?string $iso): string
    {
        if (!$iso) return 'Recently';
        try {
            $diff = now()->diffInSeconds(\Carbon\Carbon::parse($iso));
        } catch (\Throwable $e) {
            return 'Recently';
        }
        if ($diff < 60)     return 'Just now';
        if ($diff < 3600)   return floor($diff / 60) . 'm ago';
        if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return floor($diff / 604800) . 'w ago';
    }

    // ── Seed guard ────────────────────────────────────────────────────────

    private bool $seeded = false;

    private function ensureSeeded(): void
    {
        if ($this->seeded) return;
        $this->seeded = true;

        try {
            if (CommunityPost::count() === 0) {
                $this->runSeed();
            }
        } catch (\Throwable $e) {
            // Table may not exist yet (before migration) — silently skip
        }
    }

    private function runSeed(): void
    {
        foreach ($this->seedPosts() as $data) {
            CommunityPost::create([
                'id'              => $data['id'],
                'status'          => $data['status'],
                'type'            => $data['type'],
                'featured'        => $data['featured'],
                'pinned'          => $data['pinned'],
                'user_name'       => $data['user']['name'],
                'user_handle'     => $data['user']['handle'],
                'user_av'         => $data['user']['av'],
                'user_color'      => $data['user']['color'],
                'user_text_color' => $data['user']['textColor'],
                'user_skin'       => $data['user']['skin'] ?? null,
                'user_email'      => $data['user']['email'],
                'img'             => $data['img']         ?? '',
                'before_img'      => $data['before_img']  ?? '',
                'after_img'       => $data['after_img']   ?? '',
                'caption'         => $data['caption']     ?? '',
                'quote'           => $data['quote']       ?? '',
                'stars'           => $data['stars'] ?: null,
                'product'         => $data['product']     ?? '',
                'tags'            => $data['tags']        ?? [],
                'tagged_products' => $data['products']    ?? [],
                'likes_count'     => $data['likes']       ?? 0,
                'comments_count'  => count($data['comment_list'] ?? []),
                'created_at'      => $data['submitted_at'] ?? now(),
                'updated_at'      => $data['submitted_at'] ?? now(),
            ]);

            foreach ($data['comment_list'] ?? [] as $c) {
                CommunityComment::create([
                    'id'         => $c['id'],
                    'post_id'    => $data['id'],
                    'user_av'    => $c['av']    ?? 'U',
                    'user_color' => $c['color'] ?? '#C8E634',
                    'user_name'  => $c['name']  ?? 'Member',
                    'user_email' => $c['user_email'] ?? '',
                    'text'       => $c['text']  ?? '',
                    'created_at' => $c['created_at'] ?: now(),
                ]);
            }
        }
    }

    private function defaultSettings(): array
    {
        return [
            'submissions_open' => true,
            'moderation_mode'  => 'auto',
            'require_hashtag'  => false,
            'featured_post_id' => 'seed_11',
            'pinned_post_ids'  => [],
            'banned_users'     => [],
            'allow_types'      => ['photo', 'before_after', 'review', 'routine'],
        ];
    }

    private function seedPosts(): array
    {
        return [
            [
                'id' => 'seed_11', 'status' => 'approved', 'type' => 'photo',
                'featured' => true, 'pinned' => false,
                'user' => ['name' => 'Adaeze Okonkwo', 'handle' => '@adaeze.glow', 'av' => 'AO', 'color' => '#C8E634', 'textColor' => '#0A0A0A', 'skin' => 'Combination', 'email' => ''],
                'img' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=800&h=600&fit=crop',
                'before_img' => '', 'after_img' => '',
                'caption' => '3 months on the Kominhoo Glass Skin routine and I literally cannot believe this is my face. The COSRX snail serum changed everything.',
                'quote' => '', 'stars' => 0, 'product' => '',
                'tags' => ['#GlassSkin', '#KominhooResults', '#SkincareJourney'],
                'products' => ['COSRX Snail Mucin', 'Laneige Water Mask'],
                'likes' => 1247, 'saves' => 0,
                'comments' => 2,
                'comment_list' => [
                    ['id' => 'c_s1', 'av' => 'FA', 'color' => '#C8E634', 'name' => 'Funmi A.', 'text' => 'This is insane!! Your skin is literally glass 😭✨', 'created_at' => '2026-04-30T10:20:00Z', 'user_email' => ''],
                    ['id' => 'c_s2', 'av' => 'CN', 'color' => '#E8143C', 'name' => 'Chisom N.', 'text' => 'The before was already nice but the after??? 🤯', 'created_at' => '2026-04-30T10:40:00Z', 'user_email' => ''],
                ],
                'submitted_at' => '2026-04-30T10:00:00Z',
            ],
            [
                'id' => 'seed_1', 'status' => 'approved', 'type' => 'photo',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Funmi Adeleke', 'handle' => '@funmi_glow', 'av' => 'FA', 'color' => '#C8E634', 'textColor' => '#0A0A0A', 'skin' => 'Oily', 'email' => ''],
                'img' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&h=700&fit=crop',
                'before_img' => '', 'after_img' => '',
                'caption' => "Finally found my holy grail SPF! The Beauty of Joseon sunscreen doesn't leave a white cast at all 🙌",
                'quote' => '', 'stars' => 0, 'product' => '',
                'tags' => ['#SPFEveryDay', '#KBeauty', '#OilySkinCare'],
                'products' => ['Beauty of Joseon SPF'],
                'likes' => 847, 'saves' => 0,
                'comments' => 1, 'comment_list' => [
                    ['id' => 'c_s3', 'av' => 'TO', 'color' => '#3B82F6', 'name' => 'Toyin B.', 'text' => 'This is my fav SPF too! No ashiness 🙌', 'created_at' => '2026-04-30T09:45:00Z', 'user_email' => ''],
                ],
                'submitted_at' => '2026-04-30T09:00:00Z',
            ],
            [
                'id' => 'seed_2', 'status' => 'approved', 'type' => 'before_after',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Chisom Nwachukwu', 'handle' => '@chi_glows', 'av' => 'CN', 'color' => '#E8143C', 'textColor' => '#fff', 'skin' => 'Acne-Prone', 'email' => ''],
                'img' => 'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=400&h=450&fit=crop',
                'before_img' => 'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=400&h=450&fit=crop',
                'after_img' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400&h=450&fit=crop',
                'caption' => '6 weeks on Acne Rescue Bundle — dark spots fading, breakouts fewer. Consistency really is everything.',
                'quote' => '', 'stars' => 0, 'product' => '',
                'tags' => ['#AcneRecovery', '#KominhooResults'],
                'products' => ['COSRX BHA', 'Niacinamide 10%'],
                'likes' => 1103, 'saves' => 0, 'comments' => 0, 'comment_list' => [],
                'submitted_at' => '2026-04-30T07:00:00Z',
            ],
            [
                'id' => 'seed_3', 'status' => 'approved', 'type' => 'review',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Toyin Babatunde', 'handle' => '@toyinbeauty', 'av' => 'TB', 'color' => '#3B82F6', 'textColor' => '#fff', 'skin' => 'Dry', 'email' => ''],
                'img' => '', 'before_img' => '', 'after_img' => '',
                'caption' => 'The TIRTIR cushion is the best base product for dry skin. Dewy finish, no patches.',
                'quote' => 'The TIRTIR cushion is the best base product for dry skin. Dewy finish, no patches.',
                'stars' => 5, 'product' => 'TIRTIR Cushion',
                'tags' => ['#KBeauty', '#DrySkin'],
                'products' => ['TIRTIR Cushion'],
                'likes' => 623, 'saves' => 0, 'comments' => 0, 'comment_list' => [],
                'submitted_at' => '2026-04-30T05:00:00Z',
            ],
            [
                'id' => 'seed_4', 'status' => 'approved', 'type' => 'photo',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Ngozi Eze', 'handle' => '@ngozi.skin', 'av' => 'NE', 'color' => '#F59E0B', 'textColor' => '#0A0A0A', 'skin' => 'Combination', 'email' => ''],
                'img' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=600&h=500&fit=crop',
                'before_img' => '', 'after_img' => '',
                'caption' => 'Morning routine: cleanser, vitamin C, moisturiser, sunscreen. Simple works 🌿',
                'quote' => '', 'stars' => 0, 'product' => '',
                'tags' => ['#MinimalistSkincare', '#KBeauty'],
                'products' => ['COSRX Cleanser', 'Laneige', 'BoJ SPF'],
                'likes' => 519, 'saves' => 0, 'comments' => 0, 'comment_list' => [],
                'submitted_at' => '2026-04-30T04:00:00Z',
            ],
            [
                'id' => 'seed_5', 'status' => 'approved', 'type' => 'photo',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Amaka Obi', 'handle' => '@amaka.glows', 'av' => 'AO', 'color' => '#22C55E', 'textColor' => '#fff', 'skin' => 'Sensitive', 'email' => ''],
                'img' => 'https://images.unsplash.com/photo-1545208935-9a7b23524f41?w=600&h=680&fit=crop',
                'before_img' => '', 'after_img' => '',
                'caption' => 'Sensitive skin girlies — Anua Heartleaf Toner is so calming! No irritation, no redness 🌿',
                'quote' => '', 'stars' => 0, 'product' => '',
                'tags' => ['#SensitiveSkin', '#AnuaToner'],
                'products' => ['Anua Heartleaf Toner'],
                'likes' => 734, 'saves' => 0, 'comments' => 0, 'comment_list' => [],
                'submitted_at' => '2026-04-30T02:00:00Z',
            ],
            [
                'id' => 'seed_6', 'status' => 'approved', 'type' => 'review',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Kemi Adeyemi', 'handle' => '@kemi.skindiaries', 'av' => 'KA', 'color' => '#8B5CF6', 'textColor' => '#fff', 'skin' => 'Oily', 'email' => ''],
                'img' => '', 'before_img' => '', 'after_img' => '',
                'caption' => 'Glow OS Subscription — my dermatologist gasped. Worth EVERY kobo.',
                'quote' => 'Glow OS Subscription — my dermatologist gasped. Worth EVERY kobo. The box curation is insane.',
                'stars' => 5, 'product' => 'Kominhoo Glow OS',
                'tags' => ['#GlowOS', '#KominhooSubscription'],
                'products' => ['Kominhoo Glow OS'],
                'likes' => 892, 'saves' => 0, 'comments' => 0, 'comment_list' => [],
                'submitted_at' => '2026-04-29T20:00:00Z',
            ],
            [
                'id' => 'seed_7', 'status' => 'approved', 'type' => 'before_after',
                'featured' => false, 'pinned' => false,
                'user' => ['name' => 'Blessing Uche', 'handle' => '@blessin_skin', 'av' => 'BU', 'color' => '#C8E634', 'textColor' => '#0A0A0A', 'skin' => 'Hyperpigmentation', 'email' => ''],
                'img' => 'https://images.unsplash.com/photo-1582560475093-ba66accbc095?w=400&h=450&fit=crop',
                'before_img' => 'https://images.unsplash.com/photo-1582560475093-ba66accbc095?w=400&h=450&fit=crop',
                'after_img' => 'https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=400&h=450&fit=crop',
                'caption' => 'Niacinamide + Vitamin C for 2 months — hyperpigmentation is visibly lighter. Never going back.',
                'quote' => '', 'stars' => 0, 'product' => '',
                'tags' => ['#Hyperpigmentation', '#VitaminC'],
                'products' => ['The Ordinary Niacinamide', 'Klairs Vit C'],
                'likes' => 1456, 'saves' => 0, 'comments' => 0, 'comment_list' => [],
                'submitted_at' => '2026-04-29T18:00:00Z',
            ],
        ];
    }
}
