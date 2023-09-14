<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Option[]|Collection $options
 * @property string $code
*/
class Field extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'name',
        'description',
        'type',
        'code',
        'addon',
        'name_en'
    ];

    public function options()
    {
        return $this->belongsToMany(Option::class, 'field_option');
    }
}
