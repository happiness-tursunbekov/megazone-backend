<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'slogan' => $this->slogan,
            'slug' => $this->slug,
            'about' => $this->slogan,
            'icon' => new FileBrowseResource($this->icon),
            'cover' => new FileBrowseResource($this->cover),
            'address' => new AddressBrowseResource($this->address),
            'categories' => StoreCategoryBrowseResource::collection($this->activeParentCategories),
            'storeTypes' => $this->types,
            'defaultCurrencyType' => new CurrencyTypeBrowseResource($this->defaultCurrencyType),
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'website' => $this->website,
        ];
    }
}
