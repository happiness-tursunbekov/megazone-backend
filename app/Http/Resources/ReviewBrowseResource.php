<?php

namespace App\Http\Resources;

use App\Traits\ResourcePaginationCamelCase;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewBrowseResource extends JsonResource
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
            'name' => $this->name,
            'message' => $this->message,
            'rating' => $this->rating,
            'helpful' => $this->helpful,
            'unhelpful' => $this->unhelpful,
            'createdAt' => $this->createdAt ? $this->handleTimezone($this->createdAt)->diffForHumans() : null
        ];
    }
}
