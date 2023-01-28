<?php

namespace App\Http\Resources;

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
 */
class RegionResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'parent_id' => $this->parent_id,
        ];
    }
}
