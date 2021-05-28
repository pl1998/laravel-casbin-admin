<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UsersController;
use App\Http\Controllers\Auth\RolesController;
use App\Http\Controllers\Auth\PermissionsController;
use App\Http\Controllers\Auth\LogController;
use App\Http\Controllers\Auth\CaptchaController;
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


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class,'login']);  //登录
    Route::post('logout', [AuthController::class,'logout']); //注销
    Route::post('refresh', [AuthController::class,'refresh']); //刷新用户状态
    Route::put('update',[AuthController::class,'update']); //更新用户信息
    Route::post('me', [AuthController::class,'me'])->name('me')->middleware(['jwt.auth']); //
});
//系统管理
Route::group(['middleware'=>['jwt.auth','log']],function (){

   Route::group(['prefix'=>'admin','middleware'=>['permission']],function (){
       Route::get('/users',[UsersController::class,'index']);      //用户列表
       Route::post('/users',[UsersController::class,'store']);     //添加新用户;
       Route::put('users/{id}',[UsersController::class,'update']); //更新用户信息

       Route::get('/roles',[RolesController::class,'index']);          //角色列表
       Route::post('/roles',[RolesController::class,'store']);         //添加角色
       Route::put('/roles/{id}',[RolesController::class,'update']);    //更新角色
       Route::delete('/roles/{id}',[RolesController::class,'delete']); //删除角色

       Route::get('/permissions',[PermissionsController::class,'index']);          //权限列表
       Route::post('/permissions',[PermissionsController::class,'store']);         //添加权限
       Route::put('/permissions/{id}',[PermissionsController::class,'update']);    //更新权限
       Route::delete('/permissions/{id}',[PermissionsController::class,'delete']); //删除权限

       Route::get('/log',[LogController::class,'index']);          //获取日志列表
       Route::delete('/log/{id}',[LogController::class,'delete']);          //删除日志

   });
    Route::get('/admin/all_permissions',[PermissionsController::class,'allPermissions']); //获取所有权限
    Route::get('/admin/all_role',[RolesController::class,'allRule']);    //获取所有角色
});


Route::post('upload_img',[UsersController::class,'updateImg']); //头像更新

Route::post('captcha',[CaptchaController::class,'captcha']);      //获取验证码

