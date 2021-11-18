<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    use HasFactory;

    protected $table = "descuento";

    protected $fillable = [
        "es_porcentaje", "valor_descuento", "status", "validez_desde", "validez_hasta"
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        "validez_desde" => "datetime",
        "validez_hasta" => "datetime"
    ];

    public function productos()
    {
        return $this->morphToMany(Producto::class, "tipo", "tipos_promociones_productos")->withTimestamps();
    }
}
