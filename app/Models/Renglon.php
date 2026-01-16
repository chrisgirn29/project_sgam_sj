<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renglon extends Model
{
    use HasFactory;

    protected $table = 'renglones';
    protected $primaryKey = 'id_renglon';

    protected $fillable = [
        'renglon',
        'nombre',
        'grupo',
        'usuario',
        'estado'
    ];
}
