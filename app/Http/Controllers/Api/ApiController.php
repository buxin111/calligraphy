<?php

namespace App\Http\Controllers\Api;

use App\Foundation\Support\Auth;
use App\Foundation\Support\Response;
use App\Http\Controllers\Controller;

use Illuminate\Auth\AuthenticationException;

/**
 * controller 基类
 * @author wangzh
 * @date 2022-02-21
 * @package App\Http\Controllers\Api
 */
class ApiController extends Controller
{
    /**
     * 获取登录用户
     * @author wangzh
     * @date 2022-02-24
     * @throws
     * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected function auth()
    {
        // 如果没有登录就抛出异常
        throw_unless(Auth::api()->user(), AuthenticationException::class);

        return Auth::api()->user();
    }


    /**
     * 失败
     * @author wangzh
     * @date 2022-02-25
     * @param array|null|\ArrayObject| $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null, string $msg = '操作成功!')
    {
        $data = is_null($data) ? new \ArrayObject() : $data;

        return Response::successWithData($data, $msg);
    }

    /**
     * 失败
     * @author wangzh
     * @date 2022-02-25
     * @param string $msg
     * @param array|null|\ArrayObject|string[]|integer[] $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $msg, $data = null, int $code = Response::CODE_FAILED)
    {
        $data = is_null($data) ? new \ArrayObject() : $data;

        return Response::failedWithData($msg, $data, $code);
    }

    /**
     * 获取登录人ID
     * @author buxin
     * @date 2022-03-08
     * @return int
     */
    protected function getUserId(): int
    {
        return $this->auth()->id;
    }
}
