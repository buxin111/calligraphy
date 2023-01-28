<?php

namespace App\Providers;

use App\Foundation\Auth\RedisTokenGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        $this->registerCustomTokenGuard();
    }

    /**
     * 注册自定义 token 验证
     * @author wangzh
     * @date 2021-09-17
     */
    public function registerCustomTokenGuard()
    {

//        $config = [
//            'driver' => 'redis-token',
//            'provider' => 'users',
//            'conn_name' => 'default', // redis connection
//            'input_key' => 'api_token', // 请求中 token 的 key
//            'storage_key' => 'id', // 数据库中存储的主键名称
//            'token_key' => 'token_key:%s', // redis 中存储的 key 名
//        ];

        Auth::extend('redis-token', function ($app, $name, array $config) {
            // 返回一个 Illuminate\Contracts\Auth\Guard 实例...

            $conn = $this->app['redis']->connection($config['conn_name'] ?? null); // redis 链接

            return new RedisTokenGuard(
                $conn,
                Auth::createUserProvider($config['provider']),
                $this->app['request'],
                $config['input_key'] ?? 'api_token',
                $config['storage_key'] ?? 'id',
                $config['token_key'] ?? 'token_key:%s', // token_key %s 会替换为 %token
                $config['expire'] ?? -1, // token_key %s 会替换为 %token
            );
        });
    }
}
