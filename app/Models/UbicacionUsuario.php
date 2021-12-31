<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionUsuario extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones';
    protected $fillable = [
        'nombre',
        'adicional',
        'etiqueta',
        'latitud',
        'longitud',
        'usuario_id',
        'por_defecto'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'por_defecto' => 'boolean'
    ];
    //relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, "usuario_id", "usuario_id");
    }
}
