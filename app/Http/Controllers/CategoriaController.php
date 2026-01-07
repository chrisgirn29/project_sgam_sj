<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Listar todas las categorías (para DataTable AJAX)
    public function getAll()
    {
        return response()->json(Categoria::all());
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|integer',
            'usuario' => 'required|string|max:100'
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json($categoria);
    }

    // Actualizar categoría
    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado' => 'required|integer'
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());

        return response()->json($categoria);
    }

    // Cambiar estado activo/inactivo
    public function toggleEstado($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->estado = $categoria->estado == 1 ? 0 : 1;
        $categoria->save();

        return response()->json(['estado' => $categoria->estado]);
    }
}
