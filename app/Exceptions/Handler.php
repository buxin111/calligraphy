<?php

namespace App\Exceptions;

use App\Foundation\Resources\JsonResource;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @author bzxx
     * @date 2023-01-29
     * @param \Illuminate\Http\Request $request
     * @param ValidationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $firstMessage = current(collect($exception->errors())->first());

        // 转化成前端需要的格式
        $errors = collect($exception->errors())->map(function ($messages, $key) {
            return [
                "key" => $key,
                "message" => $messages,
            ];
        })->values()->toArray();

        return JsonResource::make(new \ArrayObject())->withErrors($errors)->withCode($exception->status)->withMsg($firstMessage)->response();
    }
}
