<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreCategoryFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->nameTranslated,
            'type' => $this->type,
            'code' => $this->code,
            'addon' => $this->addon,
            'pivot' => $this->pivot,
            'options' => OptionResource::collection($this->options),
            'required' => $this->required
        ];
    }
}
