<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class StoreType extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'title'
    ];

    public function getTitleAttribute()
    {
        $lang = App::getLocale();
        if ($lang && ($name = $this->getAttribute('title_' . $lang)))
            return $name;
        return $this->getRawOriginal('title');
    }
}
