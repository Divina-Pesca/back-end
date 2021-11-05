<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Utils\Res;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductoController extends Controller
{
    public function crear(Request $request)
    {
        try {
            $producto = Producto::create($request->all());
            return response()->json(
                [
                    'mensaje' => 'Producto creado',
                    'data' => $producto
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            error_log($th);
            return response()->json(
                [
                    'mensaje' => 'Producto no creado',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function obtenerTodos(Request $request)
    {
        $status = $request->query("status");

        if (!is_null($status)) {
            $categoria = Producto::where("status", $status)->get();
        } else {
            $categoria = Producto::all();
        }
        try {
            return response()->json(
                [
                    'mensaje' => 'Productos',
                    'data' => $categoria
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            print($th);
            return response()->json(
                [
                    'mensaje' => 'Productos no obtenidos'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
    public function modificar($id_producto, Request $request)
    {
        try {
            $producto = Producto::find($id_producto);
            if ($producto) {
                $producto->update($request->all());
                return response()->json(
                    [
                        'mensaje' => 'Producto modificado con exito',
                        'data' => $producto
                    ],
                    Response::HTTP_OK
                );
            }
            return response()->json(
                [
                    'mensaje' => 'producto no encontrado'
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            error_log($th);
            return response()->json(
                [
                    'mensaje' => 'Producto no modificado',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function eliminar($id_producto)
    {
        try {
            $producto = Producto::find($id_producto);
            if ($producto) {
                $producto->status = 0;
                $producto->save();
                return Res::withoutData(__("respuestas.eliminado"), Response::HTTP_OK);
            }
            return Res::withoutData(__("respuestas.no_encontrado"), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return Res::withoutData(__("respuestas.error"), Response::HTTP_NOT_FOUND);
        }
    }
    public function obtenerPorId($id_producto)
    {
        try {
            $producto = Producto::with("categoria")->find($id_producto);
            if ($producto) {
                return Res::withData($producto, __("respuestas.encontrado"), Response::HTTP_FOUND);
            }
            return Res::withoutData(__("respuestas.no_encontrado"), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
