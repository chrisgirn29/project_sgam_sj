<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /* =========================
       VISTA
    ========================= */
    public function index()
    {
        return view('proveedores.index');
    }

    /* =========================
       LISTAR (AJAX)
    ========================= */
    public function getAll()
    {
        return response()->json(
            Proveedor::orderBy('id_proveedor', 'desc')->get()
        );
    }

    /* =========================
       GUARDAR
    ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'correo'    => 'nullable|email',
            'telefono'  => 'nullable|string|max:20',
            'nit'       => 'nullable|string|max:20',
            'dpi'       => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        Proveedor::create($request->all());

        return response()->json(['message' => 'Proveedor creado']);
    }

    /* =========================
       ACTUALIZAR
    ========================= */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre'    => 'required|string|max:150',
            'correo'    => 'nullable|email',
            'telefono'  => 'nullable|string|max:20',
            'nit'       => 'nullable|string|max:20',
            'dpi'       => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor->update($request->all());

        return response()->json(['message' => 'Proveedor actualizado']);
    }

    /* =========================
       TOGGLE ESTADO
    ========================= */
    public function toggleEstado($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->estado = !$proveedor->estado;
        $proveedor->save();

        return response()->json(['message' => 'Estado actualizado']);
    }
}
