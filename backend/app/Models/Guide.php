<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'body',
        'image', 'author', 'read_time', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];
}
