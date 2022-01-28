<?php

namespace App\Http\Controllers\Auth;

use Auth;
use \Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);
            if ($this->attemptLogin($request)) {
                $usuario = $this->guard()->user();
                $usuario->generateToken();
                $usuario->likes = $usuario->productos()->count();
                $usuario->ubicacionPorDefecto;
                return response()->json([
                    'mensaje' => 'Login exitoso',
                    'data' => $usuario,
                ]);
            }

            return response()->json([
                'error' => "Error credenciales incorrectas",
            ], 401);
        } catch (\Throwable $th) {
            error_log($th);
        }
    }

    public function logout($usuario_id, Request $request)
    {
        try {
            $user = User::find($usuario_id);
            if ($user) {
                $user->api_token = null;
                $user->fcm_token = null;
                $user->save();
            }
            return response()->json(['mensaje' => 'Cerro sesion el usuario ' . $user->nombre . ' ' . $user->apellido]);
        } catch (\Throwable $th) {
            error_log($th);
            return response()->json(['mensaje' => 'Error al cerrar sesion'], Response::HTTP_BAD_REQUEST);
        }
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['error' => 'No autorizado.'], 401);
    }
}
