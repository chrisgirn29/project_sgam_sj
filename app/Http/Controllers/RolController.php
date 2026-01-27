<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
   public function index(Request $request)
{
    if ($request->expectsJson()) {
        return response()->json(
            Rol::where('estado', 1)->get()
        );
    }

    return view('roles.index');
}

     public function all()
    {
        return response()->json(
            Rol::where('estado', 1)
                ->select('id', 'rol')
                ->get()
        );
    }

    // GUARDAR
   public function store(Request $request)
{
    $data = $request->all(); // 👈 IMPORTANTE

    $rol = Rol::create([
        'rol' => $data['rol'],
        'estado' => $data['estado'] ?? 1
    ]);

    return response()->json($rol, 201);
}

public function update(Request $request, $id)
{
    $data = $request->all(); // 👈 IMPORTANTE

    $rol = Rol::findOrFail($id);
    $rol->update([
        'rol' => $data['rol'],
        'estado' => $data['estado']
    ]);

    return response()->json($rol);
}


    // CAMBIAR ESTADO
    public function cambiarEstado(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->estado = $request->estado;
        $rol->save();

        return response()->json(['success' => true]);
    }
}
