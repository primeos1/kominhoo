<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('blog_posts')) {
            $featured = null;
            $posts = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                9,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            $tags = [];
            $activeTag = trim((string) $request->query('tag', ''));
            $query     = trim((string) $request->query('q', ''));
            return view('pages.blog', compact('featured', 'posts', 'tags', 'activeTag', 'query'));
        }

        $activeTag = trim((string) $request->query('tag', ''));
        $query     = trim((string) $request->query('q', ''));

        $baseQuery = BlogPost::query()
            ->published()
            ->when($activeTag !== '', fn ($q) => $q->where('tag', $activeTag))
            ->when($query !== '', fn ($q) => $q->where('title', 'like', "%{$query}%"))
            ->orderByDesc('published_at');

        $featured = (clone $baseQuery)->where('is_featured', true)->first();

        $posts = $baseQuery
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->paginate(9)
            ->withQueryString();

        $tags = BlogPost::query()
            ->published()
            ->whereNotNull('tag')
            ->where('tag', '!=', '')
            ->distinct()
            ->orderBy('tag')
            ->pluck('tag')
            ->values()
            ->all();

        return view('pages.blog', compact('featured', 'posts', 'tags', 'activeTag', 'query'));
    }

    public function show(string $slug)
    {
        if (!Schema::hasTable('blog_posts')) {
            abort(404);
        }

        $post = BlogPost::query()->published()->where('slug', $slug)->firstOrFail();

        $related = BlogPost::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->tag, fn ($q) => $q->where('tag', $post->tag))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog_show', compact('post', 'related'));
    }
}
