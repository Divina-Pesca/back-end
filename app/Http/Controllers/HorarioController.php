<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Utils\Res;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;
use DateTimeZone;

class HorarioController extends Controller
{
    public function obtener()
    {
        try {
            $horarios = Horario::all();
            $todayUser = new DateTime("now", new DateTimeZone(config("app.timezone")));
            $todayUser = $todayUser->format("Y-m-d H:i:s");
            $today = new Carbon();
            $valido = false;
            switch ($today->dayOfWeek) {
                case Carbon::SATURDAY:
                    if ($horarios[1]["startTime"] < $todayUser &&  $todayUser > $horarios[1]["endTime"]) {
                        $valido = true;
                    }
                    break;
                case Carbon::SUNDAY:
                    if ($horarios[1]["startTime"] < $todayUser &&  $todayUser > $horarios[1]["endTime"]) {
                        $valido = true;
                    }
                    break;
                default:
                    if ($horarios[0]["startTime"] < $todayUser &&  $todayUser < $horarios[0]["endTime"]) {
                        $valido = true;
                    }
                    break;
            }
            $respuesta = [
                "valido" =>  $valido,
                "horarios" => $horarios
            ];
            return Res::withData($respuesta, __("respuestas.encontrado"), Response::HTTP_OK);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
