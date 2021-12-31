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
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    //relacion
    public function usuario()
    {
        return $this->hasOne(User::class, "id", "usuario_id");
    }
}
