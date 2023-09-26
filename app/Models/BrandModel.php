<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property BrandModel $parent
 * @property BrandModel[]|Collection $children
*/
class BrandModel extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'title',
        'brand_id',
        'category_id',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
