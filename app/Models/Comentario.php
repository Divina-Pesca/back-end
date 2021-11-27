<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;
    protected $table = "comentario";
    protected $fillable = [
        "comentario", "usuario_id"
    ];

    //relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, "usuario_id");
    }
}
