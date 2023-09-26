<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Review[]|Collection $reviews
*/
class Product extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'title',
        'brand_id',
        'model_id'
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
}
