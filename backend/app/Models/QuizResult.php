<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'skin_type', 'answers', 'recommended_product_ids', 'skin_scores'];

    protected $casts = [
        'answers'                 => 'array',
        'recommended_product_ids' => 'array',
        'skin_scores'             => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
