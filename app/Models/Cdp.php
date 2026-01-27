<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cdp extends Model
{
    use HasFactory;

    protected $table = 'cdp';

    protected $primaryKey = 'id_cdp';

    public $incrementing = true;

    protected $keyType = 'int';

  protected $fillable = [
    'id_empleado',
    'id_programa',
    'modalidad',
    'tipo_disponibilidad',
    'fecha',
    'descripcion',
    'estado',
    'monto',
    'usuario'
];
    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2'
    ];

    /* =========================
     |        RELACIONES
     ========================= */

    // CDP pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    // CDP pertenece a una unidad / programa
    public function programa()
    {
        return $this->belongsTo(Programas::class, 'id_programa', 'id_programa');
    }

    // CDP tiene un detalle presupuestario
    public function detalle()
    {
        return $this->belongsTo(CdpDetalle::class, 'id_detallecdp', 'id_detallecdp');
    }
    public function detalles()
{
    return $this->hasMany(CdpDetalle::class, 'id_cdp');
}
}
