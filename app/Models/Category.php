<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Category[] $children
 * @property Category[] $activeChildren
*/
class Category extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'name',
        'icon_id',
        'parent_id',
        'user_id',
        'max_price',
        'old_id',
        'old_type',
        'has_color',
        'size_field_id',
        'has_model',
        'has_series'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function activeChildren()
    {
        return $this->children()->where('active', true);
    }
}
