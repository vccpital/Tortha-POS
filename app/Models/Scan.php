<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'payload',
        'generated_by',
        'expires_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
