<?php
namespace App\Traits;
use Illuminate\Support\Facades\App;

/**
 * @property string $titleTranslated
*/
trait TitleTranslated {
    public function getTitleTranslatedAttribute()
    {
        $lang = App::getLocale();
        if ($lang && ($name = $this->getAttribute('title_' . $lang)))
            return $name;
        return $this->title;
    }
}
