<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;
    protected $table = "detalle_pedidos";
    protected $fillable = [
        "producto_id", "pedido_id", "precio_final", "cantidad", "subtotal"
    ];
    public $timestamps = false;
    protected $with = ["producto"];
    //relaciones
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, "pedido_id");
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, "producto_id");
    }
}
