<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use App\Traits\NameTranslated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * @property Category[]|Collection $children
 * @property Category[]|Collection $activeChildren
 * @property Brand[]|Collection $brands
 * @property BrandModel[]|Collection $brandModels
 * @property Category $parent
 * @property Category[]|Collection $parents
*/
class Category extends Model
{
    use HasFactory, ModelCamelCase, NameTranslated;

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
        'has_series',
        'name_en'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function activeChildren()
    {
        return $this->children()->where('active', true);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'category_brand');
    }

    public function brandModels()
    {
        return $this->belongsToMany(BrandModel::class, 'category_brand_model')
            ->whereNull('parent_id');
    }

    public function brandModelsByBrandId(int $brandId)
    {
        return $this->brandModels()->where('brand_id', $brandId)->get();
    }

    public function products()
    {
        return $this->belongsToMany(StoreProduct::class,
            'category_product',
            'category_id',
            'product_id',
            'id',
            'product_id'
        );
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getParentsAttribute()
    {
        $categories = new Collection();
        function getParent(Category $storeCategory, Collection &$categories) {
            $categories->push($storeCategory);
            if ($storeCategory->parent)
                getParent($storeCategory->parent, $categories);
        }

        if ($this->parent)
            getParent($this->parent, $categories);

        return $categories->reverse();
    }
}
