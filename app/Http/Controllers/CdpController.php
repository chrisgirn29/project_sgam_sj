<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programas;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use App\Models\CdpDetalle;
use App\Models\Cdp;
use Barryvdh\DomPDF\Facade\Pdf;


class CdpController extends Controller
{
public function create()
{
    $unidades = Programas::where('estado', 1)->get();
    $empleados = Empleado::where('estado', 1)->get();

    return view('cdp', compact('unidades', 'empleados'));
}

public function store(Request $request)
{
    DB::beginTransaction();

    try {

        // 1️⃣ Insertar CDP (tabla padre)
        $cdp = Cdp::create([
            'id_empleado' => $request->id_empleado,
            'id_programa' => $request->id_programa,
            'modalidad' => $request->modalidad,
            'tipo_disponibilidad' => $request->tipo_disponibilidad,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'estado' => 'FINALIZADO',
            'monto' => $request->monto,
            'usuario' => 'system'
        ]);

        // 2️⃣ Insertar TODOS los detalles
        foreach ($request->detalles as $detalle) {
            CdpDetalle::create([
                'id_cdp' => $cdp->id_cdp,
                'programa' => $detalle['programa'],
                'subprograma' => $detalle['subprograma'],
                'proyecto' => $detalle['proyecto'],
                'actividad' => $detalle['actividad'],
                'obra' => $detalle['obra'],
                'renglon' => $detalle['renglon'],
                'fuente' => $detalle['fuente'],
                'monto' => $detalle['monto'],
            ]);
        }

        DB::commit();

        return response()->json([
            'ok' => true,
            'mensaje' => 'CDP registrado correctamente'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'ok' => false,
            'mensaje' => 'Error al guardar el CDP',
            'error' => $e->getMessage()
        ], 500);
    }
}

 public function getListadoCdp()
    {
        $cdps = DB::table('cdp')
            ->join('empleados', 'empleados.id_empleado', '=', 'cdp.id_empleado')
            ->select(
                'cdp.id_cdp',
                'cdp.descripcion',
                'empleados.nombre_completo as empleado',
                'cdp.monto as total',
                'cdp.fecha'
            )
            ->orderBy('cdp.fecha', 'desc')
            ->get();

        return response()->json($cdps);
    }
public function pdfCdp($id)
{
    $cdp = DB::table('cdp')
        ->join('empleados', 'empleados.id_empleado', '=', 'cdp.id_empleado')
        ->join('programas', 'programas.id_programa', '=', 'cdp.id_programa')
        ->where('cdp.id_cdp', $id)
        ->select(
            'cdp.*',
            'empleados.nombre_completo as empleado',
            'programas.nombre as programa'
        )
        ->first();

    if (!$cdp) {
        abort(404);
    }

    $detalle = DB::table('cdp_detalle')
        ->where('id_cdp', $id)
        ->get();

    $pdf = Pdf::loadView('detailcdp', compact('cdp', 'detalle'))
        ->setPaper('letter', 'portrait');

    return $pdf->stream("CDP_{$cdp->id_cdp}.pdf");
}


}
