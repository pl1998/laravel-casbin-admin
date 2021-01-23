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

/**
 * 设置允许前端跨域
 */
Route::options('/{all}', function (Request $request) {
    $origin = $request->header('ORIGIN', '*');
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Access-Control-Request-Headers, SERVER_NAME, Access-Control-Allow-Headers, cache-control, token, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie');
})->where(['all' => '([a-zA-Z0-9-]|/)+']);


Route::any('/', function () {
    echo 'laravel-casbin-admin';
});

/**
 * namespace App\Http\Middleware\Authenticate;修改中间件方法 或者直接提供该路由响应无权限
 */
Route::get('/401',function (){
    return response()->json([
        'code'=>401,
        'message'=>'无权限'
    ],401);
})->name('401');



#用户相关
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::put('update','AuthController@update');
    Route::post('me', 'AuthController@me')->name('me')->middleware(['jwt.auth']);
});
//系统管理
Route::group(['middleware'=>['jwt.auth']],function (){

   Route::group(['prefix'=>'admin'],function (){

       Route::get('/users','UsersController@index');      //用户列表
       Route::post('/users','UsersController@store');     //添加新用户;
       Route::put('users/{id}','UsersController@update'); //更新用户信息

       Route::get('/roles','RolesController@index');          //角色列表
       Route::post('/roles','RolesController@store');         //添加角色
       Route::put('/roles/{id}','RolesController@update');    //更新角色
       Route::delete('/roles/{id}','RolesController@delete'); //删除角色

       Route::get('/permissions','PermissionsController@index');          //权限列表
       Route::post('/permissions','PermissionsController@store');         //添加权限
       Route::put('/permissions/{id}','PermissionsController@update');    //更新权限
       Route::delete('/permissions/{id}','PermissionsController@delete'); //删除权限

   });
});

# 后台用户登录
//Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    Route::post('login', 'LoginController@login');
//    Route::post('logout', 'LoginController@logout');
//    Route::post('refresh', 'LoginController@refresh');
//    Route::post('me', 'LoginController@me')->middleware(['jwt.role:admin', 'jwt.auth'])->name('me');
//});
