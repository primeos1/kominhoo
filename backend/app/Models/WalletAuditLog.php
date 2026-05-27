<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletAuditLog extends Model
{
    protected $fillable = [
        'wallet_id',
        'wallet_transaction_id',
        'user_id',
        'action',
        'payload',
        'ip_address',
        'user_agent',
        'performed_by',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transaction()
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }
}
