<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    //
     protected $table = 'recepciones';
    protected $primaryKey = 'id_recepcion';

    protected $fillable = [
        'id_requisicion',
        'id_empleado',
        'id_programa',
        'id_proveedor',
        'fecha_recepcion',
        'forma_pago',
        'serie_factura',
        'numero_factura',
        'numero_documento',
        'estado',
        'usuario'
    ];
}
