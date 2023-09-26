<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreCategoryFieldEditResource extends JsonResource
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
            'name' => $this->name,
            'nameEn' => $this->nameEn,
            'type' => $this->type,
            'code' => $this->code,
            'addon' => $this->addon,
            'pivot' => $this->pivot,
            'options' => $this->options
        ];
    }
}
