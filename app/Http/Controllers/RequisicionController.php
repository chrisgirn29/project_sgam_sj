<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Programa;
use App\Models\Programas;
use Illuminate\Support\Facades\DB;
use App\Models\Requisicion;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\RequisicionDetalleController;
use App\Models\RequisicionDetalle;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


use Illuminate\Http\Request;

class RequisicionController extends Controller
{
    // ================================
    // AJAX: Empleados
    // ================================
public function ajaxEmpleados()
{
    return response()->json(
        Empleado::where('estado', '!=', 'inactivo')
            ->select('id_empleado', 'nombre_completo', 'unidad', 'puesto')
            ->get()
    );
}

public function create()
{
    $ultimoId = Requisicion::max('id_requisicion');
    $siguienteId = $ultimoId ? $ultimoId + 1 : 1;

    return view('requisiciones.create', [
        'siguienteId' => $siguienteId
    ]);
}

public function ajaxProgramas()
{
    $programas = Programas::where('estado', 1)
        ->select('id_programa', 'nombre')
        ->get();

    return response()->json($programas);
}

public function empleadoDetalle($id)
{
    $empleado = Empleado::where('estado', 'activo')
        ->where('id_empleado', $id)
        ->select('unidad', 'puesto')
        ->first();

    return response()->json($empleado);
}

// app/Http/Controllers/RequisicionController.php
public function getEmpleadoById($id)
{
    $empleado = Empleado::find($id);

    if ($empleado) {
        return response()->json([
            'success' => true,
            'empleado' => [
                'unidad' => $empleado->unidad,
                'puesto' => $empleado->puesto
            ]
        ]);
    }

    return response()->json(['success' => false]);
}
public function getEmpleados()
{
    $empleados = Empleado::select('id_empleado', 'nombre_completo', 'unidad', 'puesto')
                          ->where('activo', 1)
                          ->orderBy('nombre_completo')
                          ->get();

    return response()->json($empleados);
}
public function productos()
{
    return DB::table('productos')
        ->select('id_producto', 'nombre', 'precio')
        ->where('estado', 1)
        ->get();
}
public function store(Request $request)
{
    try {

        DB::beginTransaction();

        // ================= CABECERA =================
        $requisicion = Requisicion::create([
            'id_empleado'    => $request->id_empleado,
            'id_programa'    => $request->id_programa,
            'tipo_solicitud' => $request->tipo_solicitud,
            'fecha'          => $request->fecha,
            'descripcion'    => $request->descripcion,
            'bitacora'       => 'en_creacion',
            'estado'         => 1,
            'usuario'        => 'sistema'
        ]);

        // ================= DETALLES =================
        // ================= DETALLES =================
if ($request->has('productos')) {

    foreach ($request->productos as $prod) {

        $cantidad = (int) $prod['cantidad'];
        $precio   = (float) $prod['precio_unitario'];

        RequisicionDetalle::create([
            'id_requisicion' => $requisicion->id_requisicion,
            'id_producto'    => $prod['id_producto'],
            'cantidad'       => $cantidad,
            'precio_unitario'=> $precio,
            'subtotal'       => $cantidad * $precio, // ✅ LÓGICA CORRECTA
            'estado'         => 1
        ]);
    }
}

        DB::commit();

        return response()->json([
            'ok' => true,
            'id_requisicion' => $requisicion->id_requisicion
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'ok' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getSolicitudesEmitidas()
{
    $data = DB::table('requisiciones as r')
        ->join('requisicion_detalles as rd', 'rd.id_requisicion', '=', 'r.id_requisicion')
        ->join('empleados as e', 'e.id_empleado', '=', 'r.id_empleado')
        ->select(
            'r.id_requisicion',
            'e.nombre_completo as empleado',
            'r.fecha',
            DB::raw('SUM(rd.subtotal) as total')
        )
        ->groupBy(
            'r.id_requisicion',
            'e.nombre_completo',
            'r.fecha'
        )
        ->orderBy('r.id_requisicion', 'asc')
        ->get();

    return response()->json($data);
}
public function getListado()
{
    $data = DB::table('requisiciones as r')
        ->join('requisicion_detalles as rd', 'rd.id_requisicion', '=', 'r.id_requisicion')
        ->join('empleados as e', 'e.id_empleado', '=', 'r.id_empleado')
        ->select(
            'r.id_requisicion',
            'e.nombre_completo as empleado',
            'r.fecha',
            DB::raw('SUM(rd.subtotal) as total')
        )
        ->groupBy(
            'r.id_requisicion',
            'e.nombre_completo',
            'r.fecha',
            'r.created_at'
        )
        ->orderBy('r.created_at', 'DESC') // 🔥 ÚLTIMO PRIMERO
        ->get();

    return response()->json($data);
}


    public function detalle($id)
    {
        return "Detalle requisición: " . $id;
    }

public function expediente($id)
{
    $nombrePrograma = DB::table('requisiciones as r')
        ->join('programas as p', 'p.id_programa', '=', 'r.id_programa')
        ->where('r.id_requisicion', $id)
        ->value('p.nombre');

    return view('viewprocedure', [
        'id_requisicion' => $id,
        'nombrePrograma' => $nombrePrograma
    ]);
}


public function verPdf($id)
{
    $requisicion = DB::table('requisiciones')
        ->where('id_requisicion', $id)
        ->first();

    $empleado = DB::table('empleados')
        ->where('id_empleado', $requisicion->id_empleado)
        ->first();

    $fechaFormateada = Carbon::parse($requisicion->fecha)
        ->locale('es')
        ->translatedFormat('d \d\e F \d\e Y');

    $pdf = Pdf::loadView('reports.oficio', [
        'id_requisicion' => $id,
        'descripcion'   => $requisicion->descripcion ?? '',
        'fecha'         => $fechaFormateada,
        'firmante'      => $empleado->nombre_completo ?? '',
        'cargoFirmante' => $empleado->puesto ?? '',
        'municipio'     => 'San Jerónimo',
        'cc'            => 'Archivo',
    ]);

    return $pdf->stream("Solicitud_$id.pdf");
}




public function prepedidoPdf($id)
{
    $requisicion = DB::table('requisiciones')
        ->where('id_requisicion', $id)
        ->first();

    if (!$requisicion) {
        abort(404);
    }

    $empleado = DB::table('empleados')
        ->where('id_empleado', $requisicion->id_empleado)
        ->first();

    $programa = DB::table('programas')
        ->where('id_programa', $requisicion->id_programa)
        ->first();

    $detalles = DB::table('requisicion_detalles as rd')
        ->join('productos as p', 'rd.id_producto', '=', 'p.id_producto')
        ->where('rd.id_requisicion', $id)
        ->select(
            'rd.cantidad',
            'p.nombre as nombre_producto'
        )
        ->get();

    $fechaFormateada = \Carbon\Carbon::parse($requisicion->fecha)
        ->format('d/m/Y');

    $pdf = Pdf::loadView('reports.prepedido', [
        'id_requisicion' => $id,
        'fecha'          => $fechaFormateada,
        'nombre'         => $empleado->nombre_completo ?? '—',
        'area'           => $empleado->unidad ?? '—',
        'puesto'         => $empleado->puesto ?? '—',
        'programa'       => $programa->nombre ?? '—',
        'observaciones'  => $requisicion->descripcion ?? '—',
        'detalles'       => $detalles
    ]);

    return $pdf->stream("prepedido_$id.pdf");
}



public function pdf($id)
{
    // Requisición + programa + empleado
    $requisicion = DB::table('requisiciones')
        ->join('programas', 'programas.id_programa', '=', 'requisiciones.id_programa')
        ->join('empleados', 'empleados.id_empleado', '=', 'requisiciones.id_empleado')
        ->where('requisiciones.id_requisicion', $id)
        ->select(
            'requisiciones.*',
            'programas.nombre as programa_nombre',
            'empleados.nombre_completo'
        )
        ->first();

    // Detalles + productos
    $detalles = DB::table('requisicion_detalles')
        ->join('productos', 'productos.id_producto', '=', 'requisicion_detalles.id_producto')
        ->where('requisicion_detalles.id_requisicion', $id)
        ->select(
            'requisicion_detalles.cantidad',
            'productos.nombre as producto_nombre'
        )
        ->get();

    $pdf = Pdf::loadView('reports.requisicion', compact('requisicion', 'detalles'))
        ->setPaper('A4');

    return $pdf->stream('requisicion_'.$id.'.pdf');
}
 public function verAutorizacion($id)
{
    Carbon::setLocale('es');

    $requisicion = Requisicion::with([
        'empleado',
        'detalles.producto'
    ])->findOrFail($id);

    $pdf = Pdf::loadView('reports.autorizacion', [
        'requisicion' => $requisicion,
        'fechaLarga'  => Carbon::parse($requisicion->fecha)
                            ->translatedFormat('d \d\e F \d\e Y')
    ])->setPaper('letter', 'portrait');

    return $pdf->stream("autorizacion_$id.pdf");
}
}

