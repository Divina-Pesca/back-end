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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//auth
Route::post('auth/login', 'Auth\LoginController@login');
Route::post('auth/registro', 'Auth\RegisterController@register');
Route::post('auth/logout', 'Auth\LoginController@logout');

//categoria
Route::get('categoria', 'CategoriaController@obtenerTodos');
Route::post('categoria', 'CategoriaController@crear');
