<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class RecepcionDetalle extends Model
{
    protected $table = 'recepcion_detalles';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_recepcion',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado'
    ];
}

