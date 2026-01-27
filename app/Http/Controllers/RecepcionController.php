<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Recepcion;
use App\Models\RecepcionDetalle;
use App\Models\RequisicionDetalle;
use App\Models\Producto;
use App\Models\Requisicion;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;


class RecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function recepcionar($id)
{
    $requisicion = Requisicion::with([
        'empleado',
        'programa',
        'detalles.producto'
    ])->findOrFail($id);

    $proveedores = Proveedor::orderBy('nombre')->get();

    return view('viewrecepview', compact(
        'requisicion',
        'proveedores'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    DB::beginTransaction();

    try {

        // ✅ VALIDACIÓN
        $request->validate([
            'id_requisicion'   => 'required|integer',
            'id_empleado'      => 'required|integer',
            'id_programa'      => 'required|integer',
            'id_proveedor'     => 'required|integer',
            'fecha_recepcion'  => 'required|date',
            'forma_pago'       => 'required|in:contado,credito,parcial',
            'numero_factura'   => 'required',
            'numero_documento' => 'required',
            'productos'        => 'required|array|min:1',
        ]);

        // ✅ INSERT RECEPCIÓN
        $idRecepcion = DB::table('recepciones')->insertGetId([
            'id_requisicion'   => $request->id_requisicion,
            'id_empleado'      => $request->id_empleado,
            'id_programa'      => $request->id_programa,
            'id_proveedor'     => $request->id_proveedor,
            'fecha_recepcion'  => $request->fecha_recepcion,
            'forma_pago'       => $request->forma_pago,
            'serie_factura'    => $request->serie_factura,
            'numero_factura'   => $request->numero_factura,
            'numero_documento' => $request->numero_documento,
            'estado'           => 'activo',
            'usuario'          => 'admin',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // ✅ DETALLES + STOCK + REQUISICIÓN
        foreach ($request->productos as $p) {

            // ➤ Insert detalle recepción
            DB::table('recepcion_detalles')->insert([
                'id_recepcion'    => $idRecepcion,
                'id_producto'     => $p['id_producto'],
                'cantidad'        => $p['cantidad'],
                'precio_unitario' => $p['precio_unitario'],
                'subtotal'        => $p['cantidad'] * $p['precio_unitario'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // ➤ Actualizar cantidad recibida
            DB::table('requisicion_detalles')
                ->where('id_requisicion', $request->id_requisicion)
                ->where('id_producto', $p['id_producto'])
                ->update([
                    'cantidad_recibida' => DB::raw('cantidad_recibida + ' . (int)$p['cantidad'])
                ]);

            // ➤ 🔥 AUMENTAR STOCK DEL PRODUCTO
            DB::table('productos')
                ->where('id_producto', $p['id_producto'])
                ->increment('stock', (int)$p['cantidad']);
        }

        DB::commit();
    return redirect()->back()->with('success', 'Recepción registrada correctamente');


    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->withErrors('Error al registrar recepción: ' . $e->getMessage());
    }
}

public function imprimirRecepcion($id)
{
    // Luego puedes usar $id para traer datos
    // Por ahora solo imprime la vista

    $pdf = Pdf::loadView('reports.recepcion')
        ->setPaper('letter', 'portrait');

    return $pdf->stream("recepcion_$id.pdf");
}
    /**
     * Display the specified resource.
     */
    public function show(Recepcion $recepcion)
    {
        //
    }
public function verPdf($idRecepcion, $idRequisicion)
    {
        $detalle = DB::select("
            SELECT
                r.id_recepcion,
                r.fecha_recepcion,
                r.serie_factura,
                r.numero_factura,

                p.nombre AS proveedor_nombre,
                p.direccion AS proveedor_direccion,
                p.nit AS proveedor_nit,

                req.descripcion AS descripcion_requisicion,

                prod.nombre AS nombre_producto,
                rd.cantidad,
                rd.precio_unitario,
                rd.subtotal

            FROM recepciones r
            INNER JOIN recepcion_detalles rd
                ON r.id_recepcion = rd.id_recepcion
            INNER JOIN proveedores p
                ON r.id_proveedor = p.id_proveedor
            INNER JOIN productos prod
                ON rd.id_producto = prod.id_producto
            INNER JOIN requisiciones req
                ON r.id_requisicion = req.id_requisicion
            WHERE r.id_recepcion = ?
              AND r.id_requisicion = ?
        ", [$idRecepcion, $idRequisicion]);

        if (empty($detalle)) {
            abort(404, 'Recepción no encontrada');
        }

        $pdf = Pdf::loadView('reports.recepcion', [
            'detalle' => $detalle,
            'header'  => $detalle[0]
        ])->setPaper('letter');

        return $pdf->stream('recepcion_'.$idRecepcion.'.pdf');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recepcion $recepcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recepcion $recepcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recepcion $recepcion)
    {
        //
    }
    public function getListadoRecepciones()
{
    $recepciones = DB::table('recepciones as r')
        ->join('recepcion_detalles as rd', 'rd.id_recepcion', '=', 'r.id_recepcion')
        ->join('proveedores as p', 'p.id_proveedor', '=', 'r.id_proveedor')
        ->select(
            'r.id_recepcion',
            'r.id_requisicion',
            'r.serie_factura',
            'r.numero_factura',
            'r.numero_documento',
            'p.nombre as proveedor',
            'r.fecha_recepcion',
            DB::raw('SUM(rd.cantidad * rd.precio_unitario) as total')
        )
        ->where('r.estado', 'ACTIVO')
        ->where('rd.estado', 'ACTIVO')
        ->groupBy(
            'r.id_recepcion',
            'r.id_requisicion',
            'r.serie_factura',
            'r.numero_factura',
            'r.numero_documento',
            'p.nombre',
            'r.fecha_recepcion'
        )
        ->orderBy('r.fecha_recepcion', 'desc')
        ->get();

    return response()->json($recepciones);
}


}
