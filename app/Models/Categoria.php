<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    protected $fillable = [
        'nombre',
        'imagen',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
    public function productosApp()
    {
        return $this->hasMany(Producto::class, 'categoria_id')->where("producto.status", 1);
    }
}
