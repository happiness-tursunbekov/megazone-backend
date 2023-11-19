<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Review[]|Collection $reviews
 * @property Category $category
*/
class Product extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'title',
        'brand_id',
        'model_id',
        'category_id',
        'views'
    ];

    public function reviews()
    {
        return $this->morphMany(Review::class, 'model');
    }

    /**
     * @param int $brandId
     * @param int $modelId
     * @return null|Product
     */
    public static function findByBrandModel(int $brandId, int $modelId)
    {
        return self::where(['brand_id' => $brandId, 'model_id' => $modelId])->first();
    }

    public function handleCategoryRelations()
    {
        $categoryIds = [$this->category->id];

        if ($this->category->parent)
            $this->category->parents->map(function (Category $category) use (&$categoryIds) {
                $categoryIds[] = $category->id;
            });

        return $this->categories()->sync($categoryIds);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
}
