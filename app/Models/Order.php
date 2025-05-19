<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'customer_id',
        'total',
        'status',
        'payment_status',
        'is_draft',
        'due_date',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'is_draft' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function mpesaTransactions()
    {
        return $this->hasMany(MpesaTransaction::class);
    }

}
