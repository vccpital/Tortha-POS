<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'sku',
        'barcode',
        'category',
        'price',
        'stock_qty',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
