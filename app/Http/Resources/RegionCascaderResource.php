<?php

namespace App\Http\Resources;

use App\Models\Region;
use App\Foundation\Resources\JsonResource;

/**
 * @author wangzh
 * @date 2022-02-25
 * @package App\Http\Resources
 *
 * @property integer id
 * @property string name
 * @property integer level
 * @property integer parent_id
 * @property \App\Models\Region[] children
 * @see \App\Models\Region
 */
class RegionCascaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'label' => $this->name,
            $this->mergeWhen($this->level <= Region::LEVEL_PROVINCE, [
                'children' => RegionCascaderResource::collection($this->whenLoaded('children'))
            ]),
        ];
    }
}
