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

//cupones
Route::get('cupon', 'CuponController@obtenerTodos');
Route::post('cupon', 'CuponController@crear');
Route::put('cupon/{cupon_id}', 'CuponController@modificar');
Route::delete('cupon/{cupon_id}', 'CuponController@eliminar');

//horario
Route::get('horario', 'HorarioController@obtener');
Route::put('horario/{horario_id}', 'HorarioController@editar');
//notificiaciones
Route::post('notificaciones/todos', 'NotificationController@pushNotificacionesTodos');
Route::post('mensaje/todos', 'NotificationController@sendMessage');

//poligonos
Route::get('poligonos', 'PoligonoController@getPoligonos');
Route::post('poligonos', 'PoligonoController@createPoligono');
Route::post('poligonos/multi', 'PoligonoController@createMultiPoligono');
Route::delete('poligonos/{poligono_id}', 'PoligonoController@deletePoligono');
Route::get('poligonos/puntos', 'PoligonoController@getPoligonsWithPoints');





//promociones
// Route::get('promocion', 'PromocionController@obetenerTodos');
// Route::post('promocion', 'PromocionController@crear');
// Route::post('promocion/{id_promocion}/descuentos', 'PromocionController@anadirDescuentos');


//user app
Route::group(['middleware' => 'auth:api'], function () {
    Route::put('usuario/editar', 'UserController@actualizarInfo');
    Route::get('usuario/productosFav', 'UserController@productosLikeados');
    Route::post('usuario/enviarComentario', 'ComentarioController@crear');
    Route::post('usuario/canjearCupon/{cupon_id}', 'UserController@canjearCupon');
    Route::get('usuario/misCupones', 'UserController@misCupones');
    Route::get('usuario/cupones', 'CuponController@cuponesApp');
    Route::post('usuario/ubicaciones', 'UbicacionUsuarioController@crear');
    Route::put('usuario/ubicaciones/{ubicacion_id}', 'UbicacionUsuarioController@editar');
    Route::get('usuario/ubicaciones', 'UbicacionUsuarioController@misUbicaciones');
    Route::get('usuario/tarjetas', 'UserController@getTarjetas');

    Route::get('usuario/ubicacionesPorDefecto', 'UserController@ubicacionPorDefecto');
    Route::get('usuario/seleccionarUbicacion/{ubicacion_id}', 'UbicacionUsuarioController@seleccionarUbicacion');
    Route::delete('usuario/ubicacion/{ubicacion_id}', 'UbicacionUsuarioController@delete');
    Route::post('usuario/fcmtoken', 'UserController@updateFcmToken');
});
Route::get('usuario/home', 'UserController@obtenerHome');

Route::post('usuario/likeProducto/{producto_id}', 'UserController@likeProducto');
Route::post('usuario/dislikeProducto/{producto_id}', 'UserController@dislikeProducto');
