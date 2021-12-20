<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Utils\Res;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CuponController extends Controller
{
    public function crear(Request $request)
    {
        try {
            $cupon = Cupon::create($request->all());
            return Res::withData($cupon, __("respuestas.creado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerTodos(Request $request)
    {
        // ->where("validez_hasta", ">=", Carbon::today())
        try {
            $status = $request->query("status");
            if (!is_null($status)) {
                $cupones = Cupon::where("status", 1)->orderBy("created_at", "desc")->get();
            } else {
                $cupones = Cupon::orderBy("created_at", "desc")->get();
            }
            return Res::withData($cupones, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function modificar($cupon_id, Request $request)
    {
        try {
            $cupon = Cupon::find($cupon_id);
            $cupon->update($request->all());
            return Res::withData($cupon, __("respuestas.modificado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function eliminar($cupon_id)
    {
        try {
            $cupon = Cupon::find($cupon_id);
            $cupon->status = 0;
            $cupon->save();
            return Res::withoutData(__("respuestas.eliminado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function cuponesApp()
    {
        try {
            $user = Auth::guard('api')->user();
            $cupones = Cupon::where("status", 1)->where("validez_hasta", ">=", Carbon::today())->orderBy("created_at", "desc")->get();
            if ($user) {
                foreach ($cupones as $cupon) {
                    $canjeado = $cupon->canjeados()->where("usuario_cupones.usuario_id", $user->getKey())->first() ? true : false;
                    $cupon["canjeado"] = $canjeado;
                }
            }
            return Res::withData($cupones, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
