<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'urlImg',
        'urlproduct',
        'category',
    ];

    // Mối quan hệ với bảng product_details
    public function details()
    {
        return $this->hasOne(ProductDetail::class);
    }

    // Mối quan hệ với bảng categories
}