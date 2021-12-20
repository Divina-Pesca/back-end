<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $table = "horario";
    protected $fillable = [
        "dias", "desde", "hasta"
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected $appends = ["startTime", "endTime"];
    public function getStartTimeAttribute()
    {
        return  date("Y-m-d H:i:s", strtotime($this->desde));
    }
    public function getEndTimeAttribute()
    {
        return  date("Y-m-d H:i:s", strtotime($this->hasta));
    }
}
