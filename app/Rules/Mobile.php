<?php

namespace App\Rules;

use Illuminate\Support\Facades\Validator;

/**
 * 手机
 * @author bzxx
 * @date 2023-01-28
 * @package App\Rules
 */
class Mobile extends BaseRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute 属性值
     * @param mixed $value
     * @return bool 它应该返回以 true 和 false 表示的属性值是否通过验证的结果
     */
    public function passes($attribute, $value)
    {
        return $this->regex($value, '/^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57]|19[0-9])[0-9]{8}$/');
    }

    /**
     * 它应该返回以 true 和 false 表示的属性值是否通过验证的结果
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be uppercase.';
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->message();
    }
}
