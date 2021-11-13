<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CategoriaController extends Controller
{
    public function crear(Request $request)
    {
        try {
            $categoria = Categoria::create($request->all());
            return response()->json(
                [
                    'mensaje' => 'Categoria creada',
                    'data' => $categoria
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            error_log($th);
            return response()->json(
                [
                    'mensaje' => 'Categoria no creada',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function obtenerTodos(Request $request)
    {
        try {

            $status = $request->query("status");
            if (!is_null($status)) {
                $categoria = Categoria::where("status", $status)->withCount('productosApp')->get();
            } else {
                $categoria = Categoria::all();
            }
            return response()->json(
                [
                    'mensaje' => 'Categorias',
                    'data' =>  $categoria
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            error_log($th);
            return response()->json(
                [
                    'mensaje' => 'Categorias no obtenidas'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
    public function modificar($id_categoria, Request $request)
    {
        try {
            $categoria = Categoria::find($id_categoria);
            if ($categoria) {
                $categoria->update($request->all());
                return response()->json(
                    [
                        'mensaje' => 'Categorias modificada con exito',
                        'data' => $categoria
                    ],
                    Response::HTTP_OK
                );
            }
            return response()->json(
                [
                    'mensaje' => 'categoria no encontrada'
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            error_log($th);
            return response()->json(
                [
                    'mensaje' => 'Categoria no modificada',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function eliminar($id_categoria)
    {
        try {
            $categoria = Categoria::find($id_categoria);
            if ($categoria) {
                $categoria->status = 0;
                $categoria->save();
                return Res::withoutData(__("respuestas.eliminado"), Response::HTTP_OK);
            }
            return Res::withoutData(__("respuestas.no_encontrado"), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return Res::withoutData(__("respuestas.error"), Response::HTTP_NOT_FOUND);
        }
    }
    public function obtenerPorId($id_categoria)
    {
        try {

            $categoria = Categoria::find($id_categoria);
            if ($categoria) {
                $user = Auth::guard('api')->user();
                if ($user) {
                    foreach ($categoria->productosApp as $producto) {
                        $isLiked = $producto->likes()->where("usuario_productos.usuario_id", $user->getKey())->first() ? true : false;
                        $producto["isLiked"] = $isLiked;
                    }
                } else {
                    $categoria->productosApp;
                }
                return Res::withData($categoria, __("respuestas.encontrado"), Response::HTTP_OK);
            }
            return Res::withoutData(__("respuestas.no_encontrado"), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
