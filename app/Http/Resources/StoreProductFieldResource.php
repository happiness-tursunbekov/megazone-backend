<?php

namespace App\Http\Resources;

use App\Models\Field;
use App\Models\Option;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreProductFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $value = match ($this->field->type) {
            Field::TYPE_SELECT => $this->value->option->titleTranslated,
            Field::TYPE_SELECT_MULTIPLE => $this->values->map(function ($val) { return $val->optoin->titleTranslated; })->join(', '),
            default => $this->value->value,
        };

        return [
            'id' => $this->id,
            'field' => new FieldBrowseResource($this->field),
            'value' => $value
        ];
    }
}
