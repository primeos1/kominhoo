<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $table = 'community_posts';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected $casts = [
        'featured'        => 'boolean',
        'pinned'          => 'boolean',
        'images'          => 'array',
        'steps'           => 'array',
        'tags'            => 'array',
        'tagged_products' => 'array',
        'likes_count'     => 'integer',
        'comments_count'  => 'integer',
        'stars'           => 'integer',
    ];

    public function likeRows()
    {
        return $this->hasMany(CommunityLike::class, 'post_id')->orderBy('created_at', 'desc');
    }

    public function commentRows()
    {
        return $this->hasMany(CommunityComment::class, 'post_id')->orderBy('created_at', 'asc');
    }
}
