<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'status',
        'note',
        'phone',
        'amount',
        'currency_id',
        'address_id',
        'user_id'
    ];
}
