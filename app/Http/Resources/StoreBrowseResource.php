<?php

namespace App\Http\Resources;

use App\Traits\ResourcePaginationCamelCase;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreBrowseResource extends JsonResource
{
    use ResourcePaginationCamelCase;
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
            'slug' => $this->slug,
            'slogan' => $this->slogan,
            'name' => $this->name,
            'cover' => $this->cover ? new FileBrowseResource($this->cover) : null,
            'icon' => $this->icon ? new FileBrowseResource($this->icon) : null,
        ];
    }
}
