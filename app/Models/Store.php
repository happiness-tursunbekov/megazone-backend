<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Address $address
 * @property File $icon
 * @property File $cover
 * @property StoreCategory[]|Collection $categories
 * @property StoreCategory[]|Collection $parentCategories
 * @property StoreCategory[]|Collection $activeParentCategories
 * @property StoreProduct[]|Collection $products
 * @property StoreType[]|Collection $types
 * @property Currency $defaultCurrencyType
*/
class Store extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'name',
        'about',
        'slug',
        'slogan',
        'active',
        'icon_id',
        'cover_id',
        'address_id',
        'default_currency_type_id',
        'instagram',
        'facebook',
        'twitter',
        'website'
    ];

    public static function findBySlug(string $slug) : self
    {
        return self::where('slug', $slug)->first();
    }

    public function cover()
    {
        return $this->belongsTo(File::class, 'cover_id');
    }

    public function icon()
    {
        return $this->belongsTo(File::class, 'icon_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function categories()
    {
        return $this->hasMany(StoreCategory::class, 'store_id')->orderBy('order');
    }

    public function parentCategories()
    {
        return $this->categories()->whereNull('parent_id');
    }

    public function activeParentCategories()
    {
        return $this->parentCategories()->where('active', '=', true);
    }

    public function products()
    {
        return $this->hasMany(StoreProduct::class, 'store_id');
    }

    public function types()
    {
        return $this->belongsToMany(StoreType::class, 'store_type');
    }

    public static function active()
    {
        return self::where('active', true);
    }

    public function defaultCurrencyType()
    {
        return $this->belongsTo(CurrencyType::class, 'default_currency_type_id');
    }
}
