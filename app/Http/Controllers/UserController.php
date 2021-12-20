<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use App\Utils\Res;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
