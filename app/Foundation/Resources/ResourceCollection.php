<?php

namespace App\Foundation\Resources;

use App\Foundation\Resources\Json\PaginatedResourceResponse;

/**
 * API 资源集合 基类
 * @author wangzh
 * @date 2022-01-26
 * @package App\Foundation\Resources
 */
class ResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
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
     * @param integer $code
     * @return $this
     */
    public function withCode(int $code): ResourceCollection
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
    public function withMsg(string $msg): ResourceCollection
    {
        $this->with['msg'] = $msg;

        return $this;
    }

    /**
     * Create a paginate-aware HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function preparePaginatedResponse($request)
    {
        if ($this->preserveAllQueryParameters) {
            $this->resource->appends($request->query());
        } elseif (! is_null($this->queryParameters)) {
            $this->resource->appends($this->queryParameters);
        }

        return (new PaginatedResourceResponse($this))->toResponse($request);
    }
}
