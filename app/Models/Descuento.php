<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    use HasFactory;

    protected $table = "descuento";

    protected $fillable = [
        "es_descuento", "valor_descuento", "promocion_id", "status"
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function promocion()
    {
        return $this->belongsTo(Promocion::class, "promocion_id");
    }

    protected $with = ['productos'];
    public function productos()
    {
        return $this->morphToMany(Producto::class, "tipo", "tipos_promociones_productos");
    }
}
