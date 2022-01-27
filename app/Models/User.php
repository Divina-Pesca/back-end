<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    protected $table = "usuario";


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'fechaNacimiento',
        'tipoUsuario',
        'metodoPagoPorDefecto',
        'email',
        'password',
        'ubicacion_por_defecto',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $with = ["ubicacionPorDefecto"];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'correoVerificadoEn' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function generateToken()
    {
        $this->api_token = Str::random(60);
        $this->save();

        return $this->api_token;
    }
    //relaciones

    public function productos()
    {
        return $this->belongsToMany(Producto::class, "usuario_productos", "usuario_id", "producto_id")->withTimestamps();
    }
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, "usuario_id");
    }
    public function ubicaciones()
    {
        return $this->hasMany(UbicacionUsuario::class, "usuario_id");
    }
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, "usuario_id");
    }
    public function cupones()
    {
        return $this->belongsToMany(Cupon::class, "usuario_cupones", "usuario_id", "cupon_id")->withTimestamps()->withPivot("usado");
    }
    public function ubicacionPorDefecto()
    {
        return $this->hasOne(UbicacionUsuario::class, "id", "ubicacion_por_defecto");;
    }
}
