<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        return view('empleados.index');
    }

    public function getAll()
    {
        return response()->json(
            Empleado::orderBy('id_empleado', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'unidad' => 'required',
            'puesto' => 'required',
            'correo' => 'required|email'
        ]);

        Empleado::create($request->all());

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($request->all());

        return response()->json(['success' => true]);
    }
    public function toggleEstado($id)
{
    $empleado = Empleado::findOrFail($id);

    $empleado->estado = $empleado->estado === 'activo'
        ? 'inactivo'
        : 'activo';

    $empleado->save();

    return response()->json([
        'success' => true,
        'estado' => $empleado->estado
    ]);
}

}
