<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use App\Traits\TitleTranslated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory, ModelCamelCase, TitleTranslated;

    protected $fillable = [
        'title',
        'title_en'
    ];
}
