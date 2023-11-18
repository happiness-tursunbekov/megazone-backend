<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parents' => CategoryBrowseResource::collection($this->parents),
            'parent' => new CategoryBrowseResource($this->parent),
            'children' => [],
            'sizes' => [],
            'colors' => [],
            'brands' => []
        ];
    }
}
