<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'store_product_id',
        'quantity',
        'color_id',
        'size_id',
        'price',
        'currency_id'
    ];
}
