<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('blog_posts')) {
            return response()->json(['success' => false, 'message' => 'Blog table missing. Run migrations.'], 503);
        }
        $posts = BlogPost::query()->orderByDesc('updated_at')->limit(200)->get();
        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function show(int $id)
    {
        if (!Schema::hasTable('blog_posts')) {
            return response()->json(['success' => false, 'message' => 'Blog table missing. Run migrations.'], 503);
        }
        $post = BlogPost::query()->findOrFail($id);
        return response()->json(['success' => true, 'data' => $post]);
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('blog_posts')) {
            return response()->json(['success' => false, 'message' => 'Blog table missing. Run migrations.'], 503);
        }
        $data = $this->validated($request);

        if (empty($data['slug'])) {
            $data['slug'] = BlogPost::makeUniqueSlug($data['title']);
        }

        $data = $this->applyPublishing($data);
        $data = $this->applyCoverImage($request, $data);

        $post = BlogPost::create($data);

        return response()->json(['success' => true, 'data' => $post]);
    }

    public function update(Request $request, int $id)
    {
        if (!Schema::hasTable('blog_posts')) {
            return response()->json(['success' => false, 'message' => 'Blog table missing. Run migrations.'], 503);
        }
        $post = BlogPost::query()->findOrFail($id);
        $data = $this->validated($request, $id);

        if (empty($data['slug'])) {
            $data['slug'] = BlogPost::makeUniqueSlug($data['title'], ignoreId: $post->id);
        }

        $data = $this->applyPublishing($data);
        $data = $this->applyCoverImage($request, $data, $post);

        $post->fill($data)->save();

        return response()->json(['success' => true, 'data' => $post]);
    }

    public function destroy(int $id)
    {
        if (!Schema::hasTable('blog_posts')) {
            return response()->json(['success' => false, 'message' => 'Blog table missing. Run migrations.'], 503);
        }
        $post = BlogPost::query()->findOrFail($id);

        if ($post->cover_image_path) {
            if (!str_starts_with($post->cover_image_path, 'http://') && !str_starts_with($post->cover_image_path, 'https://')) {
                Storage::disk('public')->delete($post->cover_image_path);
            }
        }

        $post->delete();

        return response()->json(['success' => true]);
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'slug'            => ['nullable', 'string', 'max:255', 'unique:blog_posts,slug' . ($ignoreId ? ",{$ignoreId}" : '')],
            'tag'             => ['nullable', 'string', 'max:120'],
            'excerpt'         => ['nullable', 'string', 'max:1000'],
            'content'         => ['nullable', 'string'],
            'author'          => ['nullable', 'string', 'max:120'],
            'reading_time'    => ['nullable', 'string', 'max:40'],
            'is_featured'     => ['nullable'],
            'is_published'    => ['nullable'],
            'published_at'    => ['nullable', 'date'],
            'cover_image_url' => ['nullable', 'string', 'max:2048'],
            'cover_image'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    private function applyPublishing(array $data): array
    {
        $data['is_featured']  = filter_var($data['is_featured'] ?? false, FILTER_VALIDATE_BOOL);
        $data['is_published'] = filter_var($data['is_published'] ?? false, FILTER_VALIDATE_BOOL);

        if ($data['is_published']) {
            $data['published_at'] = !empty($data['published_at'])
                ? Carbon::parse($data['published_at'])
                : now();
        } else {
            $data['published_at'] = null;
        }

        return $data;
    }

    private function applyCoverImage(Request $request, array $data, ?BlogPost $existing = null): array
    {
        if ($request->hasFile('cover_image')) {
            if ($existing?->cover_image_path) {
                if (!str_starts_with($existing->cover_image_path, 'http://') && !str_starts_with($existing->cover_image_path, 'https://')) {
                    Storage::disk('public')->delete($existing->cover_image_path);
                }
            }

            $data['cover_image_path'] = $request->file('cover_image')->store('blog', 'public');
            return $data;
        }

        $url = trim((string) ($data['cover_image_url'] ?? ''));
        unset($data['cover_image_url']);

        if ($url !== '') {
            if ($existing?->cover_image_path && !str_starts_with($existing->cover_image_path, 'http://') && !str_starts_with($existing->cover_image_path, 'https://')) {
                Storage::disk('public')->delete($existing->cover_image_path);
            }
            // External URL is stored directly in cover_image_path (for flexibility)
            // If it looks like a local storage URL, keep it as-is.
            $data['cover_image_path'] = $url;
        }

        return $data;
    }
}
