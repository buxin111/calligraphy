<?php

namespace App\Foundation\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

/**
 * 自定义 token 验证器
 * @desc 请求头中增加 $inputKey ，取出来然后 从 redis中读取 $redisKey , $redisKey 存储的是 数据表中的 唯一标识(主键编号) 通过唯一标识去获取登录用户信息
 *      $inputKey :   x-api-token
 *      $redisKey :   key => clixgo_custom_token:4c16ab0fcf7805cd73371fd730e08d27  value=> 121
 * @author wangzh
 * @date 2021-09-17
 * @package App\Foundation\Auth
 */
class RedisTokenGuard extends TokenGuard
{
    /**
     * redis 连接
     * @var Connection
     */
    protected $conn;

    /**
     * 自定义 token key
     *
     * @var string
     */
    protected $tokenKey;

    /**
     * 过期时间
     *
     * @var integer
     */
    protected $tokenExpire;

    /**
     * CustomTokenGuard constructor.
     * @param Connection $connect redis 连接
     * @param UserProvider $provider
     * @param Request $request
     * @param string $inputKey
     * @param string $storageKey 数据库中存储的主键名称
     * @param string $tokenKey
     * @param integer $tokenExpire token 过期时间  -1 永不过期
     */
    public function __construct(Connection $connect, UserProvider $provider, Request $request, string $inputKey = 'api_token', string $storageKey = 'id', string $tokenKey = 'token_key:%s', int $tokenExpire = -1)
    {
        $this->conn = $connect;

        $this->tokenKey = $tokenKey;

        $this->tokenExpire = $tokenExpire;

        parent::__construct($provider, $request, $inputKey, $storageKey, false);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            $storageValue = $this->getStorageValue($token);

            // 去数据库查用户
            $user = $this->provider->retrieveByCredentials([$this->storageKey => $storageValue]); // [ 'id'=>'12' ]
        }

        return $this->user = $user;
    }

    /**
     * 获取存储的 value
     * @author wangzh
     * @date 2021-09-17
     * @param $token
     * @return mixed
     */
    public function getStorageValue($token)
    {
        $key = sprintf($this->tokenKey, $token);

        return $this->conn->command("GET", [$key]);
    }

    /**
     * Get the token for the current request.
     *
     * @author wangzh
     * @date 2021-09-17
     * @return string
     */
    public function getTokenForRequest()
    {
        // key 是 token - role - userid
        $key = $this->request->header($this->inputKey);

        [$token] = explode('-', $key);

        return $token ?: parent::getTokenForRequest();
    }

    /**
     * 生成一个token
     * @author wangzh
     * @date 2021-09-17
     * @param string $name
     * @return string
     */
    public function createToken(string $name = ''): string
    {
        $token = hash('sha256', Str::random(40));

        return sprintf("%s%s", $name, $token);
    }

    /**
     * 登录
     * @author wangzh
     * @date 2021-09-17
     * @param AuthenticatableContract $user 登录用户
     * @param integer|null $expire 过期时间
     * @return bool | string  登录成功返回 token
     */
    public function login(AuthenticatableContract $user, int $expire = null)
    {
        $token = $this->createToken();

        $key = sprintf($this->tokenKey, $token);

        $value = $user->getAuthIdentifier();

        $expire = $expire ?: $this->tokenExpire; // 没设置就读全局设置

        if ($expire == -1) { // 永久有效
            $command = $this->conn->command("SET", [$key, $value]);
        } else {
            $command = $this->conn->command("SETEX", [$key, $this->tokenExpire, $value]); // SETEX mykey 60 redis
        }

        return $command ? $token : false;
    }

    /**
     * 尝试使用给定凭据对用户进行身份验证。
     *
     * @param array $credentials
     * @return bool|string   登录成功返回 token
     */
    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->user = $user;
            return $this->login($user);
        }

        return false;
    }

    /**
     * 确定用户是否与凭据匹配。
     *
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
}
