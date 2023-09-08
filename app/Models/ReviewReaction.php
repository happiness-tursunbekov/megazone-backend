<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReaction extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'helpful',
        'ip',
        'review_id'
    ];
}
