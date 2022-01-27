<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ComentarioController extends Controller
{
    public function crear(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                $comentario = new Comentario($request->all());
                $user->comentarios()->save($comentario);
            } else {
                $comentario = new Comentario($request->all());
            }
            return Res::withData($comentario, __("respuestas.creado"), Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerTodos()
    {
        try {
            $comentarios = Comentario::with("usuario:id,nombre,apellido")->get();
            return Res::withData($comentarios, __("respuestas.creado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
