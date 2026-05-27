<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'plan_name', 'plan_price',
        'billing_cycle', 'status', 'next_billing_date',
        'started_at', 'paused_at', 'cancelled_at', 'notes',
    ];

    protected $casts = [
        'next_billing_date' => 'date:Y-m-d',
        'started_at'        => 'datetime',
        'paused_at'         => 'datetime',
        'cancelled_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
