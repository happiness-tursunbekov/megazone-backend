<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreCategoryEditResource extends JsonResource
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
            'parentId' => $this->parentId,
            'active' => $this->active,
            'children' => self::collection($this->children),
            'open' => false,
            'hasColor' => $this->hasColor,
            'sizeFieldId' => $this->sizeFieldId
        ];
    }
}
