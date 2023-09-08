<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Str;


trait ModelCamelCase {
    public function getAttribute($key)
    {
        return parent::getAttribute(Str::snake($key)) ?? parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(Str::snake($key), $value);
    }

    public function toJson($options = null)
    {
        $res = [];
        foreach (parent::toArray() as $item => $value) {
            $camel = Str::camel($item);
            if (!@$options['fields'] || in_array($camel, $options['fields'])) $res[$camel]=$value;
        }
        return $res;
    }

    public function setAttributes(array $attrs) {
        return $this->fill($attrs);
    }

    public function fill($attributes)
    {
        $totallyGuarded = $this->totallyGuarded();

        foreach ($attributes as $key => $value) {
            $key = Str::snake($key);

            // The developers may choose to place some attributes in the "fillable" array
            // which means only those attributes may be set through mass assignment to
            // the model, and all others will just get ignored for security reasons.
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            } elseif ($totallyGuarded) {
                throw new MassAssignmentException(sprintf(
                    'Add [%s] to fillable property to allow mass assignment on [%s].',
                    $key, get_class($this)
                ));
            }
        }

        return $this;
    }

    public function saveQuietly(array $options=[])
    {
        return static::withoutEvents(function() use ($options) {
            return $this->save($options);
        });
    }
}
