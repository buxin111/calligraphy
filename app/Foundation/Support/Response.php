<?php

namespace App\Foundation\Support;

use App\Foundation\Resources\JsonResource;

/**
 * 响应消息
 * @desc
 *      关于操作码 code 设计思想:
 *          成功状态只有一种，失败状态可能有很多种。所以 0 为操作成功 其余为 操作失败， 具体的失败错误码，可以自行定义！
 *
 * @author wangzh
 * @date 2022-01-26
 * @package App\Foundation\Support
 */
class Response
{
    // 全局错误码
    public const CODE_SUCCESS = 0; // 成功
    public const CODE_FAILED = -1; // 错误(默认)

    /**
     * @desc 当你想要自定义返回结果的时候非常有用
     * @author wangzh
     * @date 2022-01-26
     * @param array| \ArrayObject $data
     * @return JsonResource
     */
    public static function make($data)
    {
        return JsonResource::make($data);
    }


    /**
     * 返回成功并附带信息
     * @author wangzh
     * @date 2022-01-26
     * @param array|\ArrayObject $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successWithData($data, string $msg = '操作成功!')
    {
        return JsonResource::make($data)->withCode(self::CODE_SUCCESS)->withMsg($msg)->response();
    }


    /**
     * 返回失败并附带信息
     * @author wangzh
     * @date 2022-01-26
     * @param string $msg
     * @param array|\ArrayObject $data
     * @param int $code 操作码应当为非 0 数
     * @return \Illuminate\Http\JsonResponse
     */
    public static function failedWithData(string $msg, $data, int $code = self::CODE_FAILED)
    {
        return JsonResource::make($data)->withCode($code)->withMsg($msg)->response();
    }
}
