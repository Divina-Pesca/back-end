<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\UbicacionUsuario;
use App\Models\User;
use App\Utils\Res;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function actualizarInfo(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->update($request->all());
            return Res::withData($user, __("respuestas.modificado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return Res::withData($th, "error", Response::HTTP_BAD_REQUEST);
        }
    }
    public function likeProducto($producto_id)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->productos()->syncWithoutDetaching($producto_id);
            return Res::withoutData(__("respuestas.like"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function dislikeProducto($producto_id)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->productos()->detach($producto_id);
            return Res::withoutData(__("respuestas.dislike"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function productosLikeados()
    {
        try {
            $user = Auth::guard('api')->user();
            $productos = $user->productos;
            return Res::withData($productos, __("respuestas.todos"), Response::HTTP_FOUND);
        } catch (\Throwable $th) {
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerHome()
    {
        try {
            $productosDescuento = Producto::has("descuentos")->get();
            $user = Auth::guard('api')->user();
            if ($user) {
                foreach ($productosDescuento as $producto) {
                    $isLiked = $producto->likes()->where("usuario_productos.usuario_id", $user->getKey())->first() ? true : false;
                    $producto["isLiked"] = $isLiked;
                }
            }
            return Res::withData($productosDescuento, __("respuestas.todos"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function canjearCupon($cupon_id)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->cupones()->attach($cupon_id);
            return Res::withoutData(__("respuestas.cupon_canjeado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function misCupones()
    {
        try {
            $user = Auth::guard('api')->user();
            $cupones = $user->cupones()->where("usado", 0)->get();
            foreach ($cupones as $cupon) {
                $cupon["esValido"] = true;

                if ($cupon["validez_hasta"] < Carbon::today()) {
                    $cupon["esValido"] = false;
                }
            }
            return Res::withData($cupones, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function ubicacionPorDefecto()
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user->ubicacion_por_defecto != 0) {
                $user->ubicacionPorDefecto;
            }
            return Res::withData($user, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function seleccionarUbicacion($ubicacion_id)
    {
        try {
            $user = Auth::guard('api')->user();
            DB::beginTransaction();
            if ($ubicacion_id != 0) {
                $ubicacion = UbicacionUsuario::find($ubicacion_id);

                if ($user->ubicacion_por_defecto == 0) {
                    $user->ubicacion_por_defecto = $ubicacion_id;
                    $ubicacion->por_defecto = 1;
                    $user->save();
                    $ubicacion->save();
                } else {
                    if ($ubicacion_id != $user->ubicacion_por_defecto) {
                        $user->ubicacionPorDefecto->por_defecto = 0;
                        $user->ubicacion_por_defecto = $ubicacion_id;
                        $ubicacion->por_defecto = 1;
                        $ubicacion->save();
                        $user->push();
                    }
                }
            } else {
                if ($user->ubicacion_por_defecto != 0) {
                    $user->ubicacionPorDefecto->por_defecto = 0;
                    $user->ubicacion_por_defecto = $ubicacion_id;
                    $user->push();
                }
            }
            $user->refresh();
            DB::commit();
            return Res::withData($user, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function updateFcmToken(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $user->fcm_token = $request->fcm_token;
            $user->save();
            return Res::withData($user, "Token FCM cambiado con exito", Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function getTarjetas()
    {
        try {
            $user = Auth::guard('api')->user();
            $unixTime = Carbon::now()->timestamp;
            $unixKey = "Y5FnbpWYtULtj1Muvw3cl8LJ7FVQfM" . $unixTime;
            $hash = hash('sha256', $unixKey);
            $token = "INNOVA-EC-SERVER" . ";" . $unixTime . ";" . $hash;
            $response = Http::withHeaders(["Auth-Token" => base64_encode($token)])->get('https://ccapi-stg.paymentez.com/v2/card/list?uid=' . $user->id);
            return Res::withData($response->json()["cards"], "tarjetas", 200);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function deleteTarjeta(Request $request)
    {
        try {
            $unixTime = Carbon::now()->timestamp;
            $unixKey = "Y5FnbpWYtULtj1Muvw3cl8LJ7FVQfM" . $unixTime;
            $hash = hash('sha256', $unixKey);
            $token = "INNOVA-EC-SERVER" . ";" . $unixTime . ";" . $hash;
            $user = Auth::guard('api')->user();
            $body = [
                "card" => ["token" => $request->token],
                "user" => ["id" => (string) $user->id]
            ];
            $response = Http::withHeaders(["Auth-Token" => base64_encode($token)])->post("https://ccapi-stg.paymentez.com/v2/card/delete/", $body);
            return Res::withData($response->json(), "tarjetas", 200);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function historicoPedidos()
    {
        try {
            $user = Auth::guard('api')->user();
            return Res::withData($user->pedidos, "pedidos", 200);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
