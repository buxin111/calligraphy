<?php

namespace App\Http\Controllers\Api;

use App\Models\Region;
use App\Http\Resources\RegionResource;
use App\Http\Resources\RegionCascaderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @author bzxx
 * @date 2023-01-28
 * @package App\Http\Controllers\Api
 */
class RegionController extends ApiController
{
    /**
     * @author bzxx
     * @date 2023-01-28
     * @return AnonymousResourceCollection
     */
    public function province()
    {
        $result = Region::province()->get();

        return RegionResource::collection($result);
    }

    /**
     * @author bzxx
     * @date 2023-01-28
     * @param $province
     * @return AnonymousResourceCollection
     */
    public function city($province)
    {
        $result = Region::city($province)->get();

        return RegionResource::collection($result);
    }

    /**
     * @author bzxx
     * @date 2023-01-28
     * @param $city
     * @return AnonymousResourceCollection
     */
    public function district($city)
    {
        $result = Region::district($city)->get();

        return RegionResource::collection($result);
    }

    /**
     * 三级联动
     * @author bzxx
     * @date 2023-01-28
     * @return AnonymousResourceCollection
     */
    public function cascader()
    {
        $result = Region::with('children.children')->province()->get();

        return RegionCascaderResource::collection($result);
    }
}
