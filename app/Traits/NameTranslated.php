<?php
namespace App\Traits;
use Illuminate\Support\Facades\App;

/** @property string $nameTranslated
*/
trait NameTranslated {
    public function getNameTranslatedAttribute()
    {
        $lang = App::getLocale();
        if ($lang && ($name = $this->getAttribute('name_' . $lang)))
            return $name;
        return $this->name;
    }
}
