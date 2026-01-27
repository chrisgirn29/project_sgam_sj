<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    //  protected $table = 'entregas';
    protected $primaryKey = 'id_entrega';

    protected $fillable = [
        'id_requisicion',
        'id_empleado',
        'id_programa',
        'descripcion',
        'numero_documento',
        'fecha_entrega',
        'estado',
        'usuario'
    ];
}
