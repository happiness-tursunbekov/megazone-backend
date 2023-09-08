<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'title',
        'brand_id',
        'category_id'
    ];
}
