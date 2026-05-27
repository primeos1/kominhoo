<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'available_balance',
        'locked_balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'available_balance' => 'decimal:2',
        'locked_balance'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(WalletAuditLog::class);
    }

    public function totalBalance(): float
    {
        return (float) $this->available_balance + (float) $this->locked_balance;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
