<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreProductResource extends JsonResource
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
            'title' => $this->title,
            'brand' => new BrandBrowseResource($this->brand),
            'model' => new ModelBrowseResource($this->brandModel),
            'price' => $this->sale ? $this->price - ($this->price * ($this->sale / 100)) : $this->price,
            'currency' => new CurrencyBrowseResource($this->currency),
            'files' => ProductFileResource::collection($this->files),
            'categories' => StoreProductCategoryResource::collection($this->storeCategories),
            'colors' => OptionResource::collection($this->colors),
            'sizes' => OptionResource::collection($this->sizes),
            'sale' => $this->sale,
            'oldPrice' => $this->price,
            'new' => $this->new,
            'rating' => $this->reviews->count() > 0 ? round(array_sum($this->reviews->pluck('rating')->toArray()) / $this->reviews->count(), 1) : 0,
            'numberOfReviews' => $this->reviews->count(),
            'description' => $this->description,
            'fields' => StoreProductFieldResource::collection($this->fields)
        ];
    }
}
