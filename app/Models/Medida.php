<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{
    protected $table = 'medidas';
    protected $primaryKey = 'id_medida';

    protected $fillable = [
        'descripcion',
        'estado',
        'usuario'
    ];
}
