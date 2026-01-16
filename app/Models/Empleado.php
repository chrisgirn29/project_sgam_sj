<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';

    protected $fillable = [
        'nombre_completo',
        'unidad',
        'puesto',
        'telefono',
        'dpi',
        'sexo',
        'tipo',
        'estado',
        'responsable',
        'director',
        'direccion',
        'edad',
        'renglon',
        'correo'
    ];
}
