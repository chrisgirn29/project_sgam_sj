<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    // Obtener datos de la empresa (JSON)
    public function index()
    {
        $empresa = Empresa::first();

        if (!$empresa) {
            // Crear un registro vacío si no existe
            $empresa = Empresa::create([
                'nombre_empresa' => '',
                'pais' => '',
                'departamento' => '',
                'municipio' => '',
                'direccion' => '',
                'correo' => '',
                'telefono' => '',
                'fax' => '',
                'pagina_web' => '',
                'moneda' => '',
                'nit' => '',
                'alcalde' => '',
                'financiero' => '',
                'logo' => null,
                'ultima_actualizacion' => now(),
            ]);
        }

        return response()->json($empresa);
    }

    // Actualizar empresa (sin depender de ID)
  public function update(Request $request)
{
    $empresa = Empresa::firstOrCreate([]);

    $data = $request->validate([
        'nombre_empresa' => 'required|string|max:255',
        'pais' => 'required|string|max:100',
        'departamento' => 'nullable|string|max:100',
        'municipio' => 'nullable|string|max:100',
        'direccion' => 'nullable|string|max:255',
        'correo' => 'nullable|email|max:150',
        'telefono' => 'nullable|string|max:50',
        'fax' => 'nullable|string|max:50',
        'pagina_web' => 'nullable|url|max:255',
        'moneda' => 'nullable|string|max:50',
        'nit' => 'nullable|string|max:50',
        'alcalde' => 'nullable|string|max:150',
        'financiero' => 'nullable|string|max:150',
        'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('public/logos');
        $data['logo'] = str_replace('public/', 'storage/', $path);
    }

    $data['ultima_actualizacion'] = now();
    $empresa->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Información actualizada correctamente'
    ]);
}

}
