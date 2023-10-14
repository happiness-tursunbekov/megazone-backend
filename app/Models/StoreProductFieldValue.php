<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Option $option
*/
class StoreProductFieldValue extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'store_product_field_id',
        'option_id',
        'value'
    ];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
