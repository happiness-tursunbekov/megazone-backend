<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property StoreCategory $storeCategory
 * @property StoreCategory[]|Collection $storeCategories
 * @property Review[]|Collection $reviews
 * @property Field[]|Collection $fields
 * @property CurrencyType $currencyType
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
        'price',
        'currency_type_id',
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

    public function storeCategories()
    {
        return $this->belongsToMany(StoreCategory::class, 'store_category_store_product');
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

    public function fields()
    {
        return $this->hasMany(StoreProductField::class, 'store_product_id');
    }

    public function saveField(Field $field, $value)
    {
        /** @var StoreProductField $productField */
        if (!($productField = $this->fields()->where('field_id', $field->id)->first()))
            $productField = $this->fields()->create([
                'fieldId' => $field->id,
            ]);

        if ($field->type == Field::TYPE_SELECT) {
            if ($productField->value)
                return $productField->value->fill(['optionId' => $value])->save();
            return $productField->values()->create(['optionId' => $value]);

        }

        if ($field->type == Field::TYPE_SELECT_MULTIPLE) {
            $productField->values()->delete();
            if (is_array($value)) {
                foreach ($value as $val) {
                    $productField->values()->create(['optionId' => $val]);
                }
                return true;
            }
            return $productField->values()->create(['optionId' => $value]);
        }

        if ($productField->value)
            return $productField->value->fill(['value' => $value])->save();
        return $productField->values()->create(['value' => $value]);
    }

    public function currencyType()
    {
        return $this->belongsTo(CurrencyType::class);
    }

    public function handleCategoryRelations()
    {
        $categoryIds = [$this->storeCategory->id];

        if ($this->storeCategory->parent)
            $this->storeCategory->parents->map(function (StoreCategory $storeCategory) use (&$categoryIds) {
                $categoryIds[] = $storeCategory->id;
            });

        return $this->storeCategories()->sync($categoryIds);
    }
}
