<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'name',
        'slogan',
        'icon_id'
    ];
}
