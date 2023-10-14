<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property StoreProductFieldValue[]|Collection $values
 * @property StoreProductFieldValue $value
 * @property Field $field
*/
class StoreProductField extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'store_product_id',
        'field_id'
    ];

    public function values()
    {
        return $this->hasMany(StoreProductFieldValue::class, 'store_product_field_id');
    }

    public function value()
    {
        return $this->hasOne(StoreProductFieldValue::class, 'store_product_field_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
