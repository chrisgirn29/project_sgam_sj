<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programas extends Model
{
    // Nombre de la tabla
    protected $table = 'programas';

    // Clave primaria personalizada
    protected $primaryKey = 'id_programa';

    // PK autoincremental
    public $incrementing = true;

    // Tipo de la PK
    protected $keyType = 'int';

    // Laravel maneja created_at y updated_at
    public $timestamps = true;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'nombre',
        'tipo',
        'estado',
        'usuario',
    ];

    // Casts
    protected $casts = [
        'estado' => 'boolean',
    ];
}
