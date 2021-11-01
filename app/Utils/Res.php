<?php

namespace App\Utils;

use Illuminate\Http\Request;

class Res
{
    static function validatorResponse($errors)
    {
        return response()->json(
            [
                'mensaje' => "Error en los parametros",
                'errores' => $errors
            ],
            400
        );
    }
    static function withData($data, $message, $code, $keyData = null)
    {
        $body = 'data';
        if (!is_null($keyData)) {
            $body = $keyData;
        }
        return response()->json(
            [
                'mensaje' => $message,
                $body => $data
            ],
            $code
        );
    }
    static function withoutData($message, $code)
    {
        return response()->json(
            [
                'mensaje' => $message
            ],
            $code
        );
    }
    static function customizedData($response, $code)
    {
        return response()->json(
            $response,
            $code
        );
    }
}
