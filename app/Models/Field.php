<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use App\Traits\NameTranslated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Option[]|Collection $options
 * @property string $code
*/
class Field extends Model
{
    use HasFactory, ModelCamelCase, NameTranslated;

    const TYPE_SELECT = 'select';
    const TYPE_SELECT_MULTIPLE = 'select_multiple';
    const TYPE_NUMBER = 'number';
    const TYPE_FLOAT = 'float';
    const TYPE_TEXT = 'text';
    const TYPE_BOOLEAN = 'boolean';

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
