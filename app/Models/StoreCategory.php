<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use App\Traits\NameTranslated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

/**
 * @property StoreCategory[] $children
 * @property StoreCategory $parent
 * @property StoreCategory[]|Collection $parents
 * @property Option[] $colors
 * @property Option[] $sizes
 * @property Field[]|Collection $fields
 * @property File $icon
 * @property Category $matchCategory
 * @property StoreCategoryFieldGroup[]|Collection $groups
 * @property BrandModel[]|Collection $brandModels
 * @property Brand[]|Collection $brands
*/
class StoreCategory extends Model
{
    use HasFactory, ModelCamelCase, NameTranslated;

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
        $categories = new Collection();
        function getParent(StoreCategory $storeCategory, Collection &$categories) {
            $categories->push($storeCategory);
            if ($storeCategory->parent)
                getParent($storeCategory->parent, $categories);
        }

        if ($this->parent)
            getParent($this->parent, $categories);

        return $categories->reverse();
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

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'store_category_brand');
    }

    public function brandModels()
    {
        return $this->belongsToMany(BrandModel::class, 'store_category_brand_model')
            ->whereNull('parent_id');
    }

    public function brandModelsByBrandId(int $brandId)
    {
        return $this->brandModels()->where('brand_id', $brandId)->get();
    }
}
