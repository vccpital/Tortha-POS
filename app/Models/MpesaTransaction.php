<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'merchantRequestId',
        'checkoutRequestId',
        'mpesaReceiptNumber',
        'phoneNumber',
        'amount',
        'transactionDate',
        'payername'
    ];

    protected $casts = [
        'transactionDate' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the order that owns the MpesaTransaction.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
