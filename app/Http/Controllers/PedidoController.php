<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Utils\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class PedidoController extends Controller
{
    public function getAll()
    {
        $pedidos = Pedido::all();
        return Res::withData($pedidos, "Pedidos", Response::HTTP_OK);
    }
    public function crearPedido(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            DB::beginTransaction();
            if ($request->metodoPago == 0) {
                $metodo = "efectivo";
            } else {
                $metodo = "xxxx " . $request->metodoPago["number"];
            }
            $pedido = Pedido::create([
                "subtotal" => $request->subtotal,
                "iva" => round($request->iva, 2),
                "descuento" => $request->tieneDescuento ? $request->valorDescuento : 0,
                "total" => round($request->totalF, 2),
                "latitud" => $request->ubicacion["latitud"],
                "longitud" => $request->ubicacion["longitud"],
                "metodo_pago" => $metodo,
                "usuario_id" => $user->id
            ]);
            foreach ($request->productos as $p) {
                $pedido->detalles()->create([
                    "producto_id" => $p["id"],
                    "precio_final" => $p["valorFinal"],
                    "cantidad" => $p["cantidad"],
                    "subtotal" => $p["subtotal"]
                ]);
            }
            if ($request->metodoPago != 0) {
                $metodo = "xxxx " . $request->metodoPago["number"];
                $unixTime = Carbon::now()->timestamp;
                $unixKey = "Y5FnbpWYtULtj1Muvw3cl8LJ7FVQfM" . $unixTime;
                $hash = hash('sha256', $unixKey);
                $token = "INNOVA-EC-SERVER" . ";" . $unixTime . ";" . $hash;
                $body = [
                    "card" => ["token" => $request->metodoPago["token"]],
                    "user" => [
                        "id" => (string) $user->id,
                        "email" => $user->email
                    ],
                    "order" => [
                        "amount" => $pedido->total,
                        "description" => "Pago",
                        "dev_reference" => (string) $pedido->id,
                        "vat" => $pedido->iva
                    ]
                ];
                $response = Http::withHeaders(["Auth-Token" => base64_encode($token)])->post("https://ccapi-stg.paymentez.com/v2/transaction/debit/", $body);
                if ($response->json()["transaction"]["status"] == "failure ") {
                    DB::rollBack();
                } else {
                    $pedido->pago_id = $response->json()["transaction"]["id"];
                    $pedido->save();
                }
            }

            $pedido->detalles;
            DB::commit();
            return Res::withData($pedido, "dasdas", 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            error_log($th);
            return Res::withoutData(__("respuestas.error"), Response::HTTP_BAD_REQUEST);
        }
    }
}
