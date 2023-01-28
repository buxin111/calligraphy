<?php

namespace App\Foundation\Resources;

use JsonSerializable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use App\Foundation\Resources\Json\AnonymousResourceCollection;

/**
 * API 资源 基类
 * @author wangzh
 * @date 2022-01-26
 * @package App\Foundation\Resources
 */
class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * 不进行包装 不然没法返回 {@link \ArrayObject}
     * @var null
     */
    public static $wrap = null;

    /**
     * @var array
     */
    public $with = [
        'code' => 0,
        'msg' => '成功',
    ];

    /**
     * 设置 code 码
     * @author wangzh
     * @date 2022-01-26
     * @param int $code
     * @return $this
     */
    public function withCode(int $code): JsonResource
    {
        $this->with['code'] = $code;

        return $this;
    }

    /**
     * 设置 msg
     * @author wangzh
     * @date 2022-01-26
     * @param string $msg
     * @return $this
     */
    public function withMsg(string $msg): JsonResource
    {
        $this->with['msg'] = $msg;

        return $this;
    }


    /**
     * @author wangzh
     * @date 2022-02-28
     * @param string $errors
     * @return $this
     */
    public function withErrors(array $errors): JsonResource
    {
        $this->with['errors'] = $errors;

        return $this;
    }

    /**
     * Resolve the resource to an array.
     *
     * @param \Illuminate\Http\Request|null $request
     * @throws
     * @return array
     */
    public function resolve($request = null)
    {
        $data = $this->toArray(
            $request = $request ?: Container::getInstance()->make('request')
        );

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        } elseif ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }

        // 这里判断如果数据是 ArrayObject 并且 里面没有内容的话就直接返回 不进行过滤
        // 没有这句话 如果是 null 或者是 空数组 返回前端的接口就会是 [] 不是 {}
        if ($data instanceof \ArrayObject && count($data) == 0) return $data;

        return $this->filter((array)$data);
    }

    /**
     * 当是 ArrayObject 的时候需要特殊处理
     * @author wangzh
     * @date 2022-01-26
     * @param \Illuminate\Http\Request $request
     * @return array|\ArrayObject|Arrayable|JsonSerializable|mixed
     */
    public function toArray($request)
    {
        return $this->resource instanceof \ArrayObject ? $this->resource : parent::toArray($request);
    }

    /**
     * 创建一个新的匿名资源集合
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
