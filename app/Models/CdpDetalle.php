<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CdpDetalle extends Model
{
    use HasFactory;

    protected $table = 'cdp_detalle';

    protected $primaryKey = 'id_detallecdp';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
    'id_cdp',
    'programa',
    'subprograma',
    'proyecto',
    'actividad',
    'obra',
    'renglon',
    'fuente',
    'monto'
];
    protected $casts = [
        'monto' => 'decimal:2'
    ];

    /* =========================
     |        RELACIONES
     ========================= */

    public function cdps()
    {
        return $this->hasMany(Cdp::class, 'id_detallecdp', 'id_detallecdp');
    }
    public function cdp()
{
    return $this->belongsTo(Cdp::class, 'id_cdp');
}
}
