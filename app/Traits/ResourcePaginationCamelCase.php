<?php

namespace App\Traits;

use App\Helpers\AnonymousResourceCollection;
use Illuminate\Support\Carbon;

trait ResourcePaginationCamelCase
{
    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }

    public function handleTimezone(Carbon $carbon)
    {
        return $carbon->shiftTimezone('Asia/Bishkek');
    }
}
