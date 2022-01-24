<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kutia\Larafirebase\Facades\Larafirebase;

class NotificationController extends Controller
{

    public function pushNotificacionesTodos(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "titulo" => "required",
            "mensaje" => "required"
        ]);
        if ($validator->fails()) {
            return Res::validatorResponse($validator->errors());
        }
        try {


            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
            Larafirebase::withTitle($request->titulo)
                ->withBody($request->mensaje)
                ->withImage($request->imagen)
                ->withPriority('high')
                ->withAdditionalData([
                    'ruta' => $request->ruta,
                    'valor' => $request->valor
                ])
                ->sendNotification($fcmTokens);
            // Larafirebase::fromArray(['title' => $request->titulo, 'body' => $request->mensaje])->sendNotification($fcmTokens);

            return Res::withData($fcmTokens, "enviado", 200);
        } catch (\Throwable $th) {
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
