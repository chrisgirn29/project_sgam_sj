<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrega;
use App\Models\EntregaDetalle;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EntregaController extends Controller
{
    public function store(Request $request)
{
    DB::transaction(function () use ($request) {

        /* ========== CABECERA ENTREGA ========== */
        $entrega = Entrega::create([
            'id_requisicion'   => $request->id_requisicion,
            'id_empleado'      => $request->id_empleado,
            'id_programa'      => $request->id_programa,
            'descripcion'      => $request->descripcion,
            'numero_documento' => $request->numero_documento,
            'fecha_entrega'    => $request->fecha_entrega,
            'estado'           => 'activo',
            'usuario'          => 'admin',
        ]);

        /* ========== DETALLE ENTREGA ========== */
        foreach ($request->productos as $p) {

            if ($p['cantidad'] <= 0) continue;

            // Guardar detalle de entrega
            EntregaDetalle::create([
                'id_entrega'      => $entrega->id_entrega,
                'id_producto'     => $p['id_producto'],
                'cantidad'        => $p['cantidad'],
                'precio_unitario' => $p['precio_unitario'],
                'subtotal'        => $p['cantidad'] * $p['precio_unitario'],
                'estado'          => 'ENTREGADO',
            ]);

            // ➤ Actualizar cantidad entregada en requisición
            DB::table('requisicion_detalles')
                ->where('id_requisicion', $request->id_requisicion)
                ->where('id_producto', $p['id_producto'])
                ->update([
                    'cantidad_entregada' => DB::raw('cantidad_entregada + ' . (int)$p['cantidad'])
                ]);

            // ➤ RESTAR STOCK DEL PRODUCTO
            DB::table('productos')
                ->where('id_producto', $p['id_producto'])
                ->decrement('stock', (int)$p['cantidad']);
        }
    });

    return redirect()->back()->with('success', 'Recepción registrada correctamente');
}
public function getListadoEntregas()
{
    $entregas = DB::table('entregas as e')
        ->leftJoin('entrega_detalles as ed', 'ed.id_entrega', '=', 'e.id_entrega')
        ->leftJoin('empleados as emp', 'emp.id_empleado', '=', 'e.id_empleado')
        ->select(
            'e.id_entrega',
            'e.id_requisicion',
            'emp.nombre_completo',
            'e.fecha_entrega',
            DB::raw('SUM(COALESCE(ed.cantidad,0) * COALESCE(ed.precio_unitario,0)) AS total')
        )
        ->groupBy(
            'e.id_entrega',
            'e.id_requisicion',
            'emp.nombre_completo',
            'e.fecha_entrega'
        )
        ->orderBy('e.fecha_entrega','desc')
        ->get();

    return response()->json(['data' => $entregas]);
}
public function imprimirEntrega($idEntrega)
{
    // ===== HEADER (1 solo registro) =====
    $header = DB::table('entregas as e')
        ->join('empleados as emp', 'emp.id_empleado', '=', 'e.id_empleado')
        ->select(
            'e.id_entrega',
            'e.fecha_entrega as fecha_recepcion',
            'e.descripcion as descripcion_requisicion',
            'emp.nombre_completo as proveedor_nombre',
            'emp.unidad as proveedor_direccion',
            DB::raw("'CF' as proveedor_nit") // opcional si no existe NIT
        )
        ->where('e.id_entrega', $idEntrega)
        ->first();

    // ===== DETALLE (muchos registros) =====
    $detalle = DB::table('entrega_detalles as ed')
        ->join('productos as p', 'p.id_producto', '=', 'ed.id_producto')
        ->select(
            'p.nombre as nombre_producto',
            'ed.cantidad',
            'ed.precio_unitario',
            DB::raw('(ed.cantidad * ed.precio_unitario) as subtotal')
        )
        ->where('ed.id_entrega', $idEntrega)
        ->get();

    //  Validación básica
    if (!$header || $detalle->isEmpty()) {
        abort(404, 'Entrega no encontrada');
    }

    // ===== PDF =====
    $pdf = Pdf::loadView('reports.entregas', compact('header', 'detalle'))
        ->setPaper('letter');

    return $pdf->stream("Entrega_{$idEntrega}.pdf");
}



}
