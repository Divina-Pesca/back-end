<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    use HasFactory;
    protected $table = "cupon";

    protected $fillable = [
        "es_porcentaje", "valor_cupon", "status", "compra_minima", "validez_hasta"
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        "validez_desde" => "datetime",
        "validez_hasta" => "datetime"
    ];
    public function canjeados()
    {
        return $this->belongsToMany(User::class, "usuario_cupones", "cupon_id", "usuario_id")->withTimestamps();
    }
}
