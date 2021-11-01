<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = "promocion";

    protected $fillable =
    [
        "nombre", "nombre_app", "imagen", "validez_desde", "validez_hasta", "status"
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        "validez_desde" => "datetime",
        "validez_hasta" => "datetime"
    ];

    public function descuentos()
    {
        return $this->hasMany(Descuento::class, "promocion_id");
    }
}
