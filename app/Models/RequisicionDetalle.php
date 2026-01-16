<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicionDetalle extends Model
{
    protected $table = 'requisicion_detalles';
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;

    protected $fillable = [
        'id_requisicion',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado'
    ];

    // ✅ ESTA RELACIÓN ES OBLIGATORIA
    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'id_producto',
            'id_producto'
        );
    }
}
