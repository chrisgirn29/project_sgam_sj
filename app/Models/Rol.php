<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rol extends Model
{
    protected $table = 'rols'; // ✅ CAMBIO CLAVE

    protected $fillable = [
        'rol',
        'estado'
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'rol', 'id');
    }
}
