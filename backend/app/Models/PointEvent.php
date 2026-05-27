<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointEvent extends Model
{
    protected $fillable = [
        'user_id', 'event_type', 'points',
        'reference_type', 'reference_id', 'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
