<?php

namespace App\Models;

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
        'imagen',
        'categoria_id',
        'stock',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function descuentos()
    {
        return $this->morphedByMany(Descuento::class, "tipo", "tipos_promociones_productos");
    }
    public function likes()
    {
        return $this->belongsToMany(User::class, "usuario_productos", "producto_id", "usuario_id")->withTimestamps();
    }
}
