<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Foundation\Support\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class LoginController
 * @author buxin
 * @date 2022-04-06
 * @package App\Http\Controllers\Api
 */
class LoginController extends ApiController
{
    /**
     * 登录
     * @author bzxx
     * @date 2023-01-28
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('name', 'id_card');

        $user = User::query()->where($credentials)->first();

        if (is_null($user)){
            return Response::failedWithData('用户或身份证号错误', ['token' => '']);
        }

        /***
         * @see \App\Foundation\Auth\RedisTokenGuard::login
         */
        if ($token = Auth::guard('api')->login($user)) {
            // 认证通过．．．
            return Response::successWithData(['token' => $token], '登录成功');
        }

        return Response::failedWithData('登录失败，请稍后重试!', ['token' => '']);
    }


    public function register(RegisterRequest $request)
    {

    }
}
