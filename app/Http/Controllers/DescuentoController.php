<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DescuentoController extends Controller
{
    public function crearDescuento(Request $request)
    {

        try {
            DB::beginTransaction();
            $descuento = Descuento::create($request->all());
            $productos = $request->input("productos");
            if ($productos) {
                $descuento->productos()->attach($productos);
            }
            DB::commit();
            $descuento->productos;
            return Res::withData($descuento, __("respuestas.creado"), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerTodos()
    {
        try {
            $descuentos = Descuento::with("productos")->orderBy("created_at", "desc")->get();
            return Res::withData($descuentos, __("respuestas.creado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
