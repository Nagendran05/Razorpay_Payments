<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    protected $fillable = [
        'product_id',
        'qty',
        'price',
        'total',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
