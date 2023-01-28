<?php

namespace App\Foundation\Resources\Json;

/**
 * @author bzxx
 * @date 2022-02-28
 * @package App\Foundation\Resources\Json
 */
class PaginatedResourceResponse extends \Illuminate\Http\Resources\Json\PaginatedResourceResponse
{
    /**
     * Add the pagination information to the response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function paginationInformation($request)
    {
        $pagination = [
            'current' => $this->resource->resource->currentPage(),
            'pageSize' => $this->resource->resource->perPage(),
            'total' => $this->resource->resource->total(),
        ];

        return ['meta' => ['paginate' => $pagination]];
    }
}
