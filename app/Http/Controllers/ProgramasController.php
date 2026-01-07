<?php

namespace App\Http\Controllers;

use App\Models\Programas;
use Illuminate\Http\Request;

class ProgramasController extends Controller
{
    public function getAll()
    {
        return response()->json(
            Programas::orderBy('id_programa', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'required|in:funcionamiento,inversion',
            'estado' => 'required|boolean',
            'usuario'=> 'required|string|max:255',
        ]);

        Programas::create($request->only([
            'nombre','tipo','estado','usuario'
        ]));

        return response()->json(['message' => 'Programa registrado']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'required|in:funcionamiento,inversion',
            'estado' => 'required|boolean',
        ]);

        $programa = Programas::findOrFail($id);
        $programa->update($request->only([
            'nombre','tipo','estado'
        ]));

        return response()->json(['message' => 'Programa actualizado']);
    }

    public function toggleEstado($id)
{
    $programa = Programas::findOrFail($id);

    $programa->estado = !$programa->estado;
    $programa->save();

    return response()->json([
        'estado' => $programa->estado
    ]);
}
public function filtrarPorAnio($anio)
{
    $programas = Programas::whereYear('created_at', $anio)
        ->orWhereYear('updated_at', $anio)
        ->orderBy('id_programa', 'desc')
        ->get();

    return response()->json($programas);
}
}
