<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    // Si update_at tiene solo una 'd', especifica esto:


    protected $fillable = [
        'id_renglon',
        'id_categoria',
        'id_medida',
        'nombre',
        'marca',
        'precio',  // Tu campo se llama 'precio', no 'precio_venta'
        'detalle',
        'id_ubicacion',
        'stock',
        'estado',
        'usuario'
    ];

    // Nota: No tienes estos campos, así que los quitamos:
    // 'precio_venta', 'precio_compra', 'precio_especial', 'precio_mayorista', 'stock_minimo'

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'estado' => 'boolean'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function medida()
    {
        return $this->belongsTo(Medida::class, 'id_medida', 'id_medida');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }
    public function renglon()
{
    return $this->belongsTo(Renglon::class, 'id_renglon', 'id_renglon');
}

}
