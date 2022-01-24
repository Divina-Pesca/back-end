<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PromocionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function obetenerTodos()
    {
        try {
            $promociones = Promocion::where("status", 1)->get();
            return Res::withData($promociones, __("respuestas.todos"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $request)
    {
        try {
            $promocion = Promocion::create($request->all());
            return Res::withData($promocion, __("respuestas.creado"), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.no_creado"), Response::HTTP_BAD_REQUEST);
        }
    }

    public function anadirDescuentos($id_promocion, Request $request)
    {
        try {
            $promocion = Promocion::find($id_promocion);
            DB::beginTransaction();
            if ($promocion) {
                $descuentos = $request->input("descuentos");
                foreach ($descuentos as $descuento) {
                    $descuentoObj = $promocion->descuentos()->create($descuento);
                    $descuentoObj->productos()->attach($descuento["productos"]);
                }
                DB::commit();
                $promocion->descuentos;
                return Res::withData($promocion, __("respuestas.creado"), Response::HTTP_CREATED);
            }
            return Res::withoutData(__("respuestas.no_encontrado"), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            DB::rollBack();
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
