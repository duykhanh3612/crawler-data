<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'category',
        'price',
        'rate',
        'quantity_sold',
        'categories',
    ];

    // Mối quan hệ với bảng products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}