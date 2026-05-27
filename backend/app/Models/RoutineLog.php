<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineLog extends Model
{
    protected $fillable = [
        'user_id', 'log_date',
        'am_steps', 'pm_steps',
        'am_done', 'pm_done',
        'pts_earned',
    ];

    protected $casts = [
        'am_steps' => 'array',
        'pm_steps' => 'array',
        'am_done'  => 'boolean',
        'pm_done'  => 'boolean',
        'log_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
