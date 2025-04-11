<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total_price'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Add accessor to use created_at as purchase_date
    public function getPurchaseDateAttribute()
    {
        return $this->created_at;
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 