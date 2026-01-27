<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaDetalle extends Model
{
    protected $table = 'entrega_detalles';
    protected $primaryKey = 'id_entrega_detalle';

    protected $fillable = [
        'id_entrega',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado',
    ];
}
