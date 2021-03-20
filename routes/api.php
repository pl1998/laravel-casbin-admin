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

/**
 * namespace App\Http\Middleware\Authenticate;修改中间件方法 或者直接提供该路由响应无权限
 */


#用户相关
Route::group(['prefix' => 'auth'], function () {

    Route::get('giteeCallback', 'Auth\OauthController@giteeCallback');
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::put('update','Auth\AuthController@update');
    Route::post('me', 'Auth\AuthController@me')->name('me')->middleware(['jwt.auth']);
});
//系统管理
Route::group(['middleware'=>['jwt.auth','log']],function (){

   Route::group(['prefix'=>'admin','middleware'=>['permission']],function (){
       Route::get('/users','Auth\UsersController@index');      //用户列表
       Route::post('/users','Auth\UsersController@store');     //添加新用户;
       Route::put('users/{id}','Auth\UsersController@update'); //更新用户信息

       Route::get('/roles','Auth\RolesController@index');          //角色列表
       Route::post('/roles','Auth\RolesController@store');         //添加角色
       Route::put('/roles/{id}','Auth\RolesController@update');    //更新角色
       Route::delete('/roles/{id}','Auth\RolesController@delete'); //删除角色

       Route::get('/permissions','Auth\PermissionsController@index');          //权限列表
       Route::post('/permissions','Auth\PermissionsController@store');         //添加权限
       Route::put('/permissions/{id}','Auth\PermissionsController@update');    //更新权限
       Route::delete('/permissions/{id}','Auth\PermissionsController@delete'); //删除权限


       Route::get('/log','Auth\LogController@index');          //获取日志列表
       Route::delete('/log/{id}','Auth\LogController@delete');          //删除日志

   });

    Route::get('/admin/all_permissions','Auth\PermissionsController@allPermissions'); //获取所有权限
    Route::get('/admin/all_role','Auth\RolesController@allRule');    //获取所有角色
});

Route::post('upload_img','Auth\UsersController@updateImg');


# 后台用户登录
//Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    Route::post('login', 'LoginController@login');
//    Route::post('logout', 'LoginController@logout');
//    Route::post('refresh', 'LoginController@refresh');
//    Route::post('me', 'LoginController@me')->middleware(['jwt.role:admin', 'jwt.auth'])->name('me');
//});
