<?php

namespace App\Foundation\Exceptions;

use ArrayObject;
use App\Foundation\Support\Response;

/**
 * 业务 Exception 基类
 * @author wangzh
 * @date 2022-01-26
 * @package App\Foundation\Exceptions
 */
class BizException extends \Exception implements BizDontReportExceptionsInterface
{
    /**
     * @var
     */
    protected $data;

    /**
     *
     * @param string $msg 错误描述
     * @param array $data 返回数据
     * @param int $code 返回 code 码
     */
    public function __construct($msg = '', $data = [], $code = Response::CODE_FAILED)
    {
        $this->data = $data;

        parent::__construct($msg, $code);
    }

    /**
     * 返回数据
     * @author wangzh
     * @date 2022-01-26
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return Response::failedWithData($this->message ?: '操作失败', $this->data ?: new ArrayObject, $this->code);
    }
}
