<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * @property StoreCategory[] $children
 * @property StoreCategory $parent
 * @property StoreCategory[] $parents
 * @property Option[] $colors
 * @property Option[] $sizes
 * @property Field[]|Collection $fields
 * @property File $icon
 * @property Category $matchCategory
 * @property StoreCategoryFieldGroup[]|Collection $groups
*/
class StoreCategory extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'name',
        'parent_id',
        'store_id',
        'match_category_id',
        'user_id',
        'max_price',
        'order',
        'has_color',
        'size_field_id',
        'active',
        'name_en'
    ];

    public static function boot() {
        parent::boot();

        static::deleting(function(self $model) {
            $model->products()->detach();
            $model->children()->delete();
        });
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getParentsAttribute()
    {
        $categories = [];
        function getParent(StoreCategory $storeCategory, &$categories) {
            $categories[] = $storeCategory;
            if ($storeCategory->parent)
                getParent($storeCategory->parent, $categories);
        }

        if ($this->parent)
            getParent($this->parent, $categories);

        return array_reverse($categories);
    }

    public function products()
    {
        return $this->belongsToMany(StoreProduct::class, 'store_category_store_product');
    }

    public function getColorsAttribute()
    {
        return Option::hydrate(
            self::join('store_category_store_product as scsp', 'scsp.store_category_id', '=', 'store_categories.id')
                ->join('store_product_file as spf', 'spf.store_product_id', '=', 'scsp.store_product_id')
                ->join('options as o', 'o.id', '=', 'spf.color_id')
                ->select('o.*')
                ->distinct('o.id')
                ->get()
                ->toArray()
        );
    }

    public function getSizesAttribute()
    {
        return Option::hydrate(
            self::join('store_category_store_product as scsp', 'scsp.store_category_id', '=', 'store_categories.id')
                ->join('store_product_size as sps', 'sps.store_product_id', '=', 'scsp.store_product_id')
                ->join('options as o', 'o.id', '=', 'sps.size_id')
                ->select('o.*')
                ->distinct('o.id')
                ->get()
                ->toArray()
        );
    }

    public function getBrandsAttribute()
    {
        return Brand::hydrate(
            self::join('store_category_store_product as scsp', 'scsp.store_category_id', '=', 'store_categories.id')
                ->join('store_products as sp', 'sp.id', '=', 'scsp.store_product_id')
                ->join('brands as b', 'b.id', '=', 'sp.brand_id')
                ->select('b.*')
                ->distinct('b.id')
                ->get()
                ->toArray()
        );
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'store_category_field');
    }

    public function groups()
    {
        return $this->hasMany(StoreCategoryFieldGroup::class);
    }

    public function icon()
    {
        return $this->belongsTo(File::class, 'icon_id');
    }

    public function matchCategory()
    {
        return $this->belongsTo(Category::class, 'match_category_id');
    }

    public function getNameAttribute()
    {
        $lang = App::getLocale();
        if ($lang && ($name = $this->getAttribute('name_' . $lang)))
            return $name;
        return $this->getRawOriginal('name');
    }
}
