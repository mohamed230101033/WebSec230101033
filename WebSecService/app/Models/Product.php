<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'code',
        'name',
        'price',
        'model',
        'description',
        'photo',
        'stock',
        'hold',
        'favourite'
    ];
    
    // Add relationship to purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
