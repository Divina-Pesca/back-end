<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
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
                    'categoria' => $categoria
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
    public function obtenerTodos()
    {
        try {
            return response()->json(
                [
                    'mensaje' => 'Categorias',
                    'categorias' => Categoria::alla()
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            print($th);
            return response()->json(
                [
                    'mensaje' => 'Categorias no obtenidas'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
