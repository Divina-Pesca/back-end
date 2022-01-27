<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = "pedidos";

    protected $fillable = [
        "subtotal", "iva", "descuento", "total", "latitud", "longitud",
        "metodo_pago", "entregado", "usuario_id", "pago_id"
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        "entregado" => "boolean"
    ];
    protected $with = ["detalles"];

    //relaciones
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, "pedido_id");
    }
    public function usuario()
    {
        return $this->hasOne(User::class, "usuario_id");
    }
}
