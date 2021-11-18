<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'producto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'peso',
        'unidad',
        'imagen',
        'categoria_id',
        'stock',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = ["valorFinal"];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function descuentos()
    {
        return $this->morphedByMany(Descuento::class, "tipo", "tipos_promociones_productos")->where("descuento.status", 1)->where("validez_desde", "<=", Carbon::today())
            ->where("validez_hasta", ">=", Carbon::today());
    }
    public function likes()
    {
        return $this->belongsToMany(User::class, "usuario_productos", "producto_id", "usuario_id")->withTimestamps();
    }
    public function getValorFinalAttribute()
    {
        try {
            $descuentos = $this->descuentos;
            $sum = 0;
            if (!empty($descuentos)) {
                foreach ($descuentos as $descuento) {
                    if ($descuento["es_porcentaje"] == 1) {
                        $sum = $sum + $this->precio * $descuento["valor_descuento"] / 100;
                    } else {
                        error_log("entro");
                        $sum = $sum +  $descuento["valor_descuento"];
                    }
                }
            }
            return $this->precio - $sum;
        } catch (\Throwable $th) {
            error_log($th);
            //throw $th;
        }
    }
}
