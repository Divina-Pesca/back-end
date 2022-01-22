<?php

namespace App\Http\Controllers;

use App\Models\Poligono;
use App\Utils\Res;
use Illuminate\Http\Request;
use SebastianBergmann\Type\ObjectType;
use Symfony\Component\HttpFoundation\Response;

class PoligonoController extends Controller
{
    function getPoligonos()
    {
        try {
            $poligonos = Poligono::all();
            return Res::withData($poligonos, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    function createPoligono(Request $request)
    {
        try {
            $poligono = Poligono::create($request->all());
            return Res::withData($poligono, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function deletePoligono($poligono_id)
    {
        try {
            $poligono = Poligono::find($poligono_id);
            if ($poligono) {
                $poligono->delete();
                return Res::withoutData(__("respuestas.eliminado"), Response::HTTP_OK);
            }
            return Res::withoutData(__("respuestas.no_encontrado"), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function createMultiPoligono(Request $request)
    {
        try {
            $poligonos = $request->poligonos;
            foreach ($poligonos as $poligonoObj) {
                $poligono = Poligono::create(["puntos" => $poligonoObj]);
            }
            return Res::withoutData("Todos los poligonos creados", Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
    public function getPoligonsWithPoints()
    {
        try {
            $poligonos = Poligono::all();
            $arrayPol = array();
            foreach ($poligonos as $poligono) {
                $puntos = json_decode($poligono["puntos"]);
                $pol = array();
                foreach ($puntos as $punto) {
                    $puntosO = array($punto->lat, $punto->lng);
                    $pol[] = $puntosO;
                }
                // $punto[] = $pol["lat"];
                // $punto[] = $pol["lng"];
                $arrayPol[] = $pol;
            }
            return Res::withData($arrayPol, "Puntos", Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
