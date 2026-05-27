<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityComment extends Model
{
    protected $table = 'community_comments';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = ['created_at' => 'datetime'];

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'post_id');
    }
}
