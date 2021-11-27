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
            // foreach ($productos as $p) {
            //     $producto = Producto::find($p);
            //     $producto->descuentos()->sync($descuento);
            // }
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
            $descuentos = Descuento::orderBy("created_at", "desc")->get();
            return Res::withData($descuentos, __("respuestas.creado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function eliminar($descuento_id)
    {
        try {
            $descuento = Descuento::find($descuento_id);
            $descuento->status = 0;
            $descuento->save();
            return Res::withoutData(__("respuestas.eliminado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return Res::withoutData(__("respuestas.error"), Response::HTTP_NOT_FOUND);
        }
    }
    public function modificar($descuento_id, Request $request)
    {
        try {
            DB::beginTransaction();
            $descuento = Descuento::find($descuento_id);
            $descuento->update($request->all());
            $productos = $request->input("productos");
            if ($productos) {
                $descuento->productos()->sync($productos);
            }
            DB::commit();
            $descuento->productos;
            return Res::withData($descuento, __("respuestas.modificado"), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerProductosPorDescuento($descuento_id)
    {
        try {
            $productos = Descuento::find($descuento_id)->productos;
            return Res::withData($productos, __("respuestas.modificado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
