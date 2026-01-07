<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use Illuminate\Http\Request;

class MedidaController extends Controller
{
    // Listar todas las medidas (para DataTable AJAX)
    public function getAll()
    {
        return response()->json(Medida::all());
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|integer',
            'usuario' => 'required|string|max:100'
        ]);

        $medida = Medida::create($request->all());

        return response()->json($medida);
    }

    // Actualizar categoría
    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|integer'
        ]);

        $medida = Medida::findOrFail($id);
        $medida->update($request->all());

        return response()->json($medida);
    }

    // Cambiar estado activo/inactivo
    public function toggleEstado($id)
    {
        $medida = Medida::findOrFail($id);
        $medida->estado = $medida->estado == 1 ? 0 : 1;
        $medida->save();

        return response()->json(['estado' => $medida->estado]);
    }
}
