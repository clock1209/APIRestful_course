<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUCT_AVAILABLE = 'disponible';
    const PRODUCT_NOT_AVAILABLE = 'no disponible';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function isAvailable()
    {
        return $this->status == static::PRODUCT_AVAILABLE;
    }
}
