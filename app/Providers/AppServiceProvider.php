<?php

namespace App\Providers;

use App\Foundation\Support\IdCard;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->registerValidator();


    }

    /**
     * 注册 验证规则
     * @author wangzh
     * @date 2022-02-18
     */
    private function registerValidator()
    {
        // 注册 验证规则
        // 验证手机号
        Validator::extend('mobile', \App\Rules\Mobile::class);

        //验证身份证号
        Validator::extend('identityCard', function ($attribute, $value, $parameters, $validator) {
            return IdCard::checkNumber($value);
        });
        Validator::replacer('identityCard', function ($message, $attribute, $rule, $parameters) {
            return '身份证号校验不通过';
        });
    }
}
