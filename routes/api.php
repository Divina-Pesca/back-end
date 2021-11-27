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
Route::get('categoria/{id_categoria}', 'CategoriaController@obtenerPorId');
Route::post('categoria', 'CategoriaController@crear');
Route::put('categoria/{id_categoria}', 'CategoriaController@modificar');
Route::delete('categoria/{id_categoria}', 'CategoriaController@eliminar');

//producto
Route::get('producto', 'ProductoController@obtenerTodos');
Route::get('producto/{id_producto}', 'ProductoController@obtenerPorId');
Route::post('producto', 'ProductoController@crear');
Route::put('producto/{id_producto}', 'ProductoController@modificar');
Route::delete('producto/{id_producto}', 'ProductoController@eliminar');

//descuento
Route::post('descuento', 'DescuentoController@crearDescuento');
Route::get('descuento', 'DescuentoController@obtenerTodos');
Route::put('descuento/{descuento_id}', 'DescuentoController@modificar');
Route::delete('descuento/{descuento_id}', 'DescuentoController@eliminar');
Route::get('descuento/{descuento_id}/productos', 'DescuentoController@obtenerProductosPorDescuento');


//comentario
Route::get('comentario', 'ComentarioController@obtenerTodos');



//promociones
// Route::get('promocion', 'PromocionController@obetenerTodos');
// Route::post('promocion', 'PromocionController@crear');
// Route::post('promocion/{id_promocion}/descuentos', 'PromocionController@anadirDescuentos');


//user app
Route::group(['middleware' => 'auth:api'], function () {
    Route::put('usuario/editar', 'UserController@actualizarInfo');
    Route::get('usuario/productosFav', 'UserController@productosLikeados');
    Route::post('usuario/enviarComentario', 'ComentarioController@crear');
});
Route::get('usuario/home', 'UserController@obtenerHome');

Route::post('usuario/likeProducto/{producto_id}', 'UserController@likeProducto');
Route::post('usuario/dislikeProducto/{producto_id}', 'UserController@dislikeProducto');
