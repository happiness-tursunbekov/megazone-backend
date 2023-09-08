<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property ReviewReaction[]|Collection $reactions
 * @property integer $helpful
 * @property integer $unhelpful
 * @property User $user
*/
class Review extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'name',
        'message',
        'model_type',
        'model_id',
        'hide',
        'active',
        'user_id',
        'rating'
    ];

    public function reactions()
    {
        return $this->hasMany(ReviewReaction::class);
    }

    public function getHelpfulAttribute()
    {
        return $this->reactions()->where('helpful', '=', true)->count();
    }

    public function getUnhelpfulAttribute()
    {
        return $this->reactions()->where('helpful', '=', false)->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
