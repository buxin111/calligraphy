<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户注册
 * @author bzxx
 * @date 2023-01-28
 * @package App\Http\Requests
 *
 * @property string name 作者姓名
 * @property string id_card 身份证号
 * @property string email 邮箱账号
 * @property string mobile 手机号
 * @property int province_id 省份id
 * @property int city_id 城市id
 *
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Func rule
     * @author buxin
     * @date 2022-03-07
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'id_card' => ['required', 'identityCard', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'mobile' => ['required', 'mobile', 'unique:users'],
            'province_id' => ['required', 'integer'],
            'city_id' => ['required', 'integer'],
        ];
    }

    /**
     * Func messages
     * @author buxin
     * @date 2022-03-07
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => '请填写作者姓名',
            'id_card.required' => '请填写身份证号',
            'id_card.identityCard' => '身份证号格式不正确',
            'id_card.unique' => '身份证号已存在',
            'email.required' => '请填写邮箱账号',
            'email.email' => '邮箱账号格式不正确',
            'email.unique' => '邮箱账号已存在',
            'mobile.required' => '请填写手机号',
            'mobile.mobile' => '手机号格式不正确',
            'mobile.unique' => '手机号已存在',
            'province_id.required' => '请选择省',
            'province_id.integer' => 'province_id 必须是一个整数',
            'city_id.required' => '请选择市',
            'city_id.integer' => 'city_id 必须是一个整数',
        ];
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @return string
     */
    public function getIdCard()
    {
        return $this->id_card;
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @return int
     */
    public function getProvinceId()
    {
        return $this->province_id;
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @return int
     */
    public function getCityId()
    {
        return $this->city_id;
    }
}
