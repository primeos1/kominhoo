<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityLike extends Model
{
    protected $table = 'community_likes';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime'];

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'post_id');
    }
}
