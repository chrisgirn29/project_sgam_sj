<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresa'; // nombre de la tabla

    protected $primaryKey = 'id_empresa'; // clave primaria correcta

    public $incrementing = true; // si es autoincrement

    protected $keyType = 'int'; // tipo de la PK

    protected $fillable = [
        'nombre_empresa', 'pais', 'departamento', 'municipio', 'direccion',
        'correo', 'telefono', 'fax', 'pagina_web', 'moneda', 'nit',
        'alcalde', 'financiero', 'logo', 'ultima_actualizacion'
    ];

    protected $casts = [
        'ultima_actualizacion' => 'datetime',
    ];
}
