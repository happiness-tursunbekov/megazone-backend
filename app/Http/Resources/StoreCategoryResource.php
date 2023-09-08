<?php

namespace App\Http\Resources;

use App\Models\StoreCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreCategoryResource extends JsonResource
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
            'children' => StoreCategoryBrowseResource::collection($this->children),
            'colors' => OptionResource::collection($this->colors),
            'sizes' => OptionResource::collection($this->sizes),
            'brands' => BrandBrowseResource::collection($this->brands),
            'parents' => StoreCategoryBrowseResource::collection($this->parents)
        ];
    }
}
