<?php

namespace App\Internal\User\Resources;

use App\Foundation\Resources\JsonResource;

/**
 * Class UserResource
 * @author wangzh
 * @date 2022-02-25
 * @package App\Internal\User\Resources
 *
 * @property integer id
 * @property integer corp_id 所属公司
 * @property string name 用户名
 * @property string avatar 头像
 * @property string mobile 手机号
 * @property string email 邮箱
 * @property string email_verified_at 邮箱验证时间
 * @property string password 密码
 * @property string status 状态 0:禁用 1:启用
 * @property \Illuminate\Support\Carbon|string created_at 添加时间
 * @property \Illuminate\Support\Carbon|string updated_at 修改时间
 */
class UserResource extends JsonResource
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
            'name' => $this->name, // 用户名
            'avatar' => $this->avatar, // 头像
            'mobile' => $this->mobile,// 手机号
            'status' => $this->status, // 状态 0:禁用 1:启用
            'register_time' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
