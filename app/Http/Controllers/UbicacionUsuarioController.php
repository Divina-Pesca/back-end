<?php

namespace App\Http\Controllers;

use App\Models\UbicacionUsuario;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UbicacionUsuarioController extends Controller
{
    public function crear(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $ubicacion = new UbicacionUsuario($request->all());
            $user->ubicaciones()->save($ubicacion);
            return Res::withData($ubicacion, __("respuestas.creado"), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerTodos()
    {
        try {
            $ubicaciones = UbicacionUsuario::with("usuario:id,nombre,apellido")->get();
            return Res::withData($ubicaciones, __("respuestas.creado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function misUbicaciones()
    {
        try {
            $user = Auth::guard('api')->user();
            return Res::withData($user->ubicaciones, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function editar($ubicacion_id, Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            $ubicacion = UbicacionUsuario::find($ubicacion_id);
            $ubicacion->update($request->all());
            $user->refresh();
            return Res::withData($user, __("respuestas.creado"), Response::HTTP_CREATED);
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
    public function delete($ubicacion_id)
    {
        try {
            $user = Auth::guard('api')->user();
            $ubicacion = UbicacionUsuario::find($ubicacion_id);
            if($ubicacion_id==$user->ubicacion_por_defecto){
                $user->ubicacion_por_defecto = 0;
                $user->save();
            }
            $ubicacion->delete();
            $user->refresh();

            return Res::withData($user, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
