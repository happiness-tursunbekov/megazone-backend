<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'full_path',
        'lat',
        'lng'
    ];
}
