<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Res;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function actualizarInfo($usuario_id, Request $request)
    {
        try {
            $user = User::find($usuario_id);
            $user->update($request->all());
            return Res::withData($user, __("respuestas.modificado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return Res::withData($th, "error", Response::HTTP_BAD_REQUEST);

        }
    }
    public function likeProducto($usuario_id, $producto_id)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
