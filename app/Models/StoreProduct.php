<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property StoreCategory $storeCategory
 * @property StoreCategory[] $storeCategories
 * @property Review[]|Collection $reviews
*/
class StoreProduct extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'title',
        'description',
        'product_id',
        'store_id',
        'brand_id',
        'model_id',
        'series_id',
        'price',
        'currency_id',
        'country_id',
        'store_category_id',
        'sale',
        'new'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function brandModel()
    {
        return $this->belongsTo(BrandModel::class, 'model_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'store_product_file')->withPivot('color_id', 'in_stock');
    }

    public function storeCategory()
    {
        return $this->belongsTo(StoreCategory::class);
    }

    public function getStoreCategoriesAttribute()
    {
        $categories = [];

        if ($this->storeCategory->parent)
            array_push($categories, ...$this->storeCategory->parents);
        array_push($categories, $this->storeCategory);

        return $categories;
    }

    public function colors()
    {
        return $this->belongsToMany(
            Option::class,
            'store_product_file',
            'store_product_id',
            'color_id')->distinct();
    }

    public function sizes()
    {
        return $this->belongsToMany(
            Option::class,
            'store_product_size',
            'store_product_id',
            'size_id')->distinct();
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'model');
    }
}
