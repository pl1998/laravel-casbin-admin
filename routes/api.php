<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


# 普通用户登录
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me')->name('me')->middleware(['jwt.auth']);
});

# 后台用户登录
//Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    Route::post('login', 'LoginController@login');
//    Route::post('logout', 'LoginController@logout');
//    Route::post('refresh', 'LoginController@refresh');
//    Route::post('me', 'LoginController@me')->middleware(['jwt.role:admin', 'jwt.auth'])->name('me');
//});
