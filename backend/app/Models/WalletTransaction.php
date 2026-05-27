<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'reference',
        'transaction_type',
        'source',
        'category',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'metadata',
        'description',
        'idempotency_key',
        'related_order_id',
        'related_payment_reference',
        'processed_by',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after'  => 'decimal:2',
        'metadata'       => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
