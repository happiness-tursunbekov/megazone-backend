<?php

namespace App\Models;

use App\Traits\ModelCamelCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory, ModelCamelCase;

    protected $fillable = [
        'type_id',
        'value'
    ];

    public function type()
    {
        return $this->belongsTo(CurrencyType::class, 'type_id');
    }
}
