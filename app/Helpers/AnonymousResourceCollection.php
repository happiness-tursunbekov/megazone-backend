<?php

namespace App\Helpers;

class AnonymousResourceCollection extends \Illuminate\Http\Resources\Json\AnonymousResourceCollection
{
    protected function preparePaginatedResponse($request) {
        if ($this->preserveAllQueryParameters) {
            $this->resource->appends($request->query());
        } elseif (! is_null($this->queryParameters)) {
            $this->resource->appends($this->queryParameters);
        }
        return (new PaginatedResourceResponse($this))->toResponse($request);
    }
}
