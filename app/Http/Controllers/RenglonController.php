<?php

namespace App\Http\Controllers;

use App\Models\Renglon;
use Illuminate\Http\Request;

class RenglonController extends Controller
{
    /* Vista */
    public function index()
    {
        return view('viewrenglones');
    }

    /* Obtener todos los renglones */
    public function getAll()
    {
        return response()->json(
            Renglon::orderBy('renglon')->get()
        );
    }

    /* Guardar */
    public function store(Request $request)
    {
        $request->validate([
            'renglon' => 'required|numeric',
            'nombre'  => 'required|string',
            'grupo'   => 'required|numeric',
            'estado'  => 'required|boolean',
            'usuario' => 'required|string'
        ]);

        Renglon::create($request->all());

        return response()->json(['message' => 'Renglón creado']);
    }

    /* Actualizar */
    public function update(Request $request, $id)
    {
        $request->validate([
            'renglon' => 'required|numeric',
            'nombre'  => 'required|string',
            'grupo'   => 'required|numeric',
            'estado'  => 'required|boolean'
        ]);

        $renglon = Renglon::findOrFail($id);
        $renglon->update($request->all());

        return response()->json(['message' => 'Renglón actualizado']);
    }

    /* Cambiar estado */
    public function toggleEstado($id)
    {
        $renglon = Renglon::findOrFail($id);
        $renglon->estado = !$renglon->estado;
        $renglon->save();

        return response()->json(['message' => 'Estado actualizado']);
    }
}
