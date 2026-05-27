<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'author_name', 'caption', 'image', 'tags', 'likes', 'status'];

    protected $casts = ['tags' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
