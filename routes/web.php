<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',function (){
    echo "<h3 style='display: flex;justify-content: center'>
laravel-casbin-admin 基于laravel8.x开发前后端分离的后台通用框架
</h3>
";
});

