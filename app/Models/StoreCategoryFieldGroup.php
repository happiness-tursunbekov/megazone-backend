<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use App\Traits\NameTranslated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Field[]|Collection $fields
*/
class StoreCategoryFieldGroup extends Model
{
    use HasFactory, ModelCamelCase, NameTranslated;

    protected $fillable = [
        'name',
        'name_en',
        'store_category_id'
    ];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'store_category_field');
    }
}
