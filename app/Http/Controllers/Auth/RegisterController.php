<?php

namespace App\Http\Controllers\Auth;

use \Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers {
        register as baseRegister;
    }
    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'nombre' => ['required', 'string', 'max:255'],
                'apellido' => ['required', 'string', 'max:255'],
                'telefono' => ['required', 'string', 'max:255'],
                'fechaNacimiento' => ['required', 'date', 'before:-18 years'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:usuario'],
                'password' => ['required', 'string', 'min:1', 'confirmed'],
                'tipoUsuario' => ['required', 'string', 'max:255'],


            ],
            [

                "unique" => "Usuario ya registrado",
                "before" => 'Usuario menor de edad',
                "email" => "Debe ser un correo válido."
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'apellido' => $data['apellido'],
            'fechaNacimiento' => $data['fechaNacimiento'],
            'telefono' => $data['telefono'],
            'password' => Hash::make($data['password']),
            'metodoPagoPorDefecto' => !isset($data['metodoPagoPorDefecto']) ? null : $data['metodoPagoPorDefecto'],
            'tipoUsuario' => $data['tipoUsuario'],
            'ubicacion_por_defecto' => 0


        ]);
    }
    protected function registered(Request $request, $usuario)
    {
        return response()->json(
            [
                'mensaje' => 'Usuario creado con exito',
                'usuario' => $usuario
            ],
            Response::HTTP_CREATED
        );
    }
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(
                [
                    'mensaje' => 'Parametros invalidos',
                    'errores' => $validator->errors()
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return $this->baseRegister($request);
        }
    }
}
