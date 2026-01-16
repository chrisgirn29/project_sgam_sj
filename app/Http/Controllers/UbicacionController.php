<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    public function index()
    {
        return view('ubicaciones.index');
    }

    public function getAll()
    {
        return response()->json(
            Ubicacion::orderBy('id_ubicacion', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:150',
            'usuario' => 'required|string',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $ubicacion = Ubicacion::create([
            'descripcion' => $request->descripcion,
            'usuario' => $request->usuario,
            'estado' => $request->estado ?? 'activo'
        ]);

        return response()->json([
            'success' => true,
            'data' => $ubicacion
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:150',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $ubicacion = Ubicacion::findOrFail($id);
        $ubicacion->update([
            'descripcion' => $request->descripcion,
            'estado' => $request->estado
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function toggleEstado($id)
    {
        $ubicacion = Ubicacion::findOrFail($id);

        $ubicacion->estado =
            $ubicacion->estado === 'activo'
                ? 'inactivo'
                : 'activo';

        $ubicacion->save();

        return response()->json([
            'estado' => $ubicacion->estado
        ]);
    }
}
