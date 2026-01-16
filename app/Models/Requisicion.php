<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisicion extends Model
{
    protected $table = 'requisiciones';
    protected $primaryKey = 'id_requisicion';
    public $incrementing = true;

    protected $fillable = [
        'id_empleado',
        'id_programa',
        'tipo_solicitud',
        'fecha',
        'descripcion',
        'bitacora',
        'estado',
        'usuario'
    ];

    // ✅ PROGRAMA
    public function programa()
    {
        return $this->belongsTo(Programas::class, 'id_programa', 'id_programa');
    }

    // ✅ EMPLEADO
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    // ✅ DETALLES
    public function detalles()
    {
        return $this->hasMany(RequisicionDetalle::class, 'id_requisicion', 'id_requisicion');
    }
}
