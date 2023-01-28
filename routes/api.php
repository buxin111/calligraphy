<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Internal\User\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [\App\Http\Controllers\Api\LoginController::class, 'authenticate']);

// 用户模块
Route::middleware('auth:api')->prefix('user')->group(function () {
    Route::get('/info', [UserController::class, 'info']);
});
