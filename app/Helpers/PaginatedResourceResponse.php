<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PaginatedResourceResponse extends \Illuminate\Http\Resources\Json\PaginatedResourceResponse
{
    protected function meta($paginated) {
        return $this->toCamelCase(Arr::except($paginated, [
            'links', // Add here the links to be excluded from the response
            'data',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
            'path'
        ]));
    }

    /**
     * Add the pagination information to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function paginationInformation($request)
    {
        $paginated = $this->resource->resource->toArray();

        return [
            'meta' => $this->meta($paginated),
        ];
    }

    private function toCamelCase($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[Str::camel($key)] = $value;
        }
        return $result;
    }
}
