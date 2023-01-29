<?php


namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

abstract class BaseRule implements Rule
{
    /**
     * Validator::extend 必须实现这个方法
     * @author wangzh
     * @date 2021-11-23
     * @param mixed $attributes
     * @param mixed $value
     * @param array $parameters
     * @param \Illuminate\Validation\ Validator $validator
     * @return bool
     * @see \Illuminate\Support\Facades\Validator::extend
     */
    public function validate($attributes, $value, $parameters, $validator)
    {
        return $this->passes($attributes, $value);
    }

    /**
     * 使用正则验证数据
     * @author wangzh
     * @date 2021-11-23
     * @access protected
     * @param mixed $value 字段值
     * @param string $rule 验证规则 正则规则或者预定义正则名
     * @return bool
     */
    protected function regex($value, $rule)
    {
        if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            // 不是正则表达式则两端补上/
            $rule = '/^' . $rule . '$/';
        }
        return 1 === preg_match($rule, (string)$value);
    }
}
