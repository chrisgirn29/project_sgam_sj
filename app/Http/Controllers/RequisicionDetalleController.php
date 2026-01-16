<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequisicionDetalle;

class RequisicionDetalleController extends Controller
{
    public function store(Request $request)
    {
        foreach ($request->productos as $p) {
            RequisicionDetalle::create([
                'id_requisicion'  => $request->id_requisicion,
                'id_producto'     => $p['id_producto'],
                'cantidad'        => $p['cantidad'],
                'precio_unitario' => $p['precio_unitario'],
                'subtotal'        => $p['subtotal'],
                'estado'          => 1
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
