<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Medida;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    public function index()
    {
        return view('productos.bajas');
    }
public function imprimirKardex($id_producto)
{
    // ===== VARIABLES MYSQL =====
    DB::statement("SET @saldo_cantidad := 0");
    DB::statement("SET @saldo_total := 0");

    // ===== PRODUCTO + MEDIDA =====
    $producto = DB::table('productos as pr')
        ->join('medidas as m', 'm.id_medida', '=', 'pr.id_medida')
        ->select(
            'pr.id_producto',
            'pr.nombre',
            'm.descripcion as medida'
        )
        ->where('pr.id_producto', $id_producto)
        ->first();

    // ===== KARDEX =====
    $kardex = DB::select("
        SELECT
            k.proveedor_destino,
            k.fecha_evento,
            k.fecha_entrada,
            k.numero_factura,
            k.cantidad_entrada,
            k.valor_unitario_entrada,
            k.valor_total_entrada,
            k.no_salida,
            k.cantidad_salida,
            k.valor_unitario_salida,
            k.valor_total_salida,

            (@saldo_cantidad := @saldo_cantidad
                + k.cantidad_entrada
                - k.cantidad_salida) AS saldo_cantidad,

            k.saldo_unitario_evento AS saldo_precio_unitario,

            (@saldo_total := @saldo_total
                + k.valor_total_entrada
                - k.valor_total_salida) AS saldo_total

        FROM (

            /* ===== RECEPCIONES ===== */
            SELECT
                p.nombre AS proveedor_destino,
                r.fecha_recepcion AS fecha_evento,
                r.fecha_recepcion AS fecha_entrada,
                r.numero_factura,
                rd.cantidad AS cantidad_entrada,
                rd.precio_unitario AS valor_unitario_entrada,
                rd.subtotal AS valor_total_entrada,

                NULL AS no_salida,
                0 AS cantidad_salida,
                0 AS valor_unitario_salida,
                0 AS valor_total_salida,

                rd.precio_unitario AS saldo_unitario_evento,
                1 AS orden_evento

            FROM recepcion_detalles rd
            JOIN recepciones r ON r.id_recepcion = rd.id_recepcion
            JOIN proveedores p ON p.id_proveedor = r.id_proveedor
            WHERE rd.id_producto = ?

            UNION ALL

            /* ===== ENTREGAS ===== */
            SELECT
                emp.nombre_completo AS proveedor_destino,
                e.fecha_entrega AS fecha_evento,
                NULL,
                NULL,
                0,
                0,
                0,

                e.numero_documento AS no_salida,
                ed.cantidad AS cantidad_salida,
                ed.precio_unitario AS valor_unitario_salida,
                ed.subtotal AS valor_total_salida,

                ed.precio_unitario AS saldo_unitario_evento,
                2 AS orden_evento

            FROM entrega_detalles ed
            JOIN entregas e ON e.id_entrega = ed.id_entrega
            JOIN empleados emp ON emp.id_empleado = e.id_empleado
            WHERE ed.id_producto = ?

        ) k
        ORDER BY k.fecha_evento, k.orden_evento
    ", [$id_producto, $id_producto]);

    // ===== PDF =====
    return Pdf::loadView(
            'reports.kardex',
            compact('kardex', 'producto')
        )
        ->setPaper('legal', 'landscape')
        ->stream('kardex_inventario.pdf');
}
public function kardex($id_producto)
{
    // ===== VARIABLES MYSQL =====
    DB::statement("SET @saldo_cantidad := 0");
    DB::statement("SET @saldo_total := 0");

    $kardex = DB::select("
        SELECT
            k.proveedor_destino,
            k.fecha_evento,
            k.fecha_entrada,
            k.numero_factura,
            k.cantidad_entrada,
            k.valor_unitario_entrada,
            k.valor_total_entrada,
            k.no_salida,
            k.cantidad_salida,
            k.valor_unitario_salida,
            k.valor_total_salida,

            (@saldo_cantidad := @saldo_cantidad
                + k.cantidad_entrada
                - k.cantidad_salida) AS saldo_cantidad,

            k.saldo_unitario_evento AS saldo_precio_unitario,

            (@saldo_total := @saldo_total
                + k.valor_total_entrada
                - k.valor_total_salida) AS saldo_total

        FROM (
            /* ===== RECEPCIONES ===== */
            SELECT
                p.nombre AS proveedor_destino,
                r.fecha_recepcion AS fecha_evento,
                r.fecha_recepcion AS fecha_entrada,
                r.numero_factura,
                rd.cantidad AS cantidad_entrada,
                rd.precio_unitario AS valor_unitario_entrada,
                rd.subtotal AS valor_total_entrada,

                NULL AS no_salida,
                0 AS cantidad_salida,
                0 AS valor_unitario_salida,
                0 AS valor_total_salida,

                rd.precio_unitario AS saldo_unitario_evento,
                1 AS orden_evento

            FROM recepcion_detalles rd
            JOIN recepciones r ON r.id_recepcion = rd.id_recepcion
            JOIN proveedores p ON p.id_proveedor = r.id_proveedor
            WHERE rd.id_producto = ?

            UNION ALL

            /* ===== ENTREGAS ===== */
            SELECT
                emp.nombre_completo AS proveedor_destino,
                e.fecha_entrega AS fecha_evento,
                NULL,
                NULL,
                0,
                0,
                0,

                e.numero_documento AS no_salida,
                ed.cantidad AS cantidad_salida,
                ed.precio_unitario AS valor_unitario_salida,
                ed.subtotal AS valor_total_salida,

                ed.precio_unitario AS saldo_unitario_evento,
                2 AS orden_evento

            FROM entrega_detalles ed
            JOIN entregas e ON e.id_entrega = ed.id_entrega
            JOIN empleados emp ON emp.id_empleado = e.id_empleado
            WHERE ed.id_producto = ?
        ) k
        ORDER BY k.fecha_evento, k.orden_evento
    ", [$id_producto, $id_producto]);

    /* ===== INFO DEL PRODUCTO + MEDIDA ===== */
    $producto = DB::table('productos as pr')
        ->join('medidas as m', 'm.id_medida', '=', 'pr.id_medida')
        ->select(
            'pr.id_producto',
            'pr.nombre',
            'm.descripcion as medida'
        )
        ->where('pr.id_producto', $id_producto)
        ->first();

    return view('viewkardex', compact(
        'kardex',
        'id_producto',
        'producto'
    ));
}
    public function bajasPDF()
    {
        $productos = Producto::join('categorias', 'productos.id_categoria', '=', 'categorias.id_categoria')
            ->select(
                'productos.id_producto',
                'productos.nombre',
                'productos.stock',
                'productos.stock_minimo',
                'categorias.descripcion as categoria_nombre'
            )
            ->whereColumn('productos.stock', '<', 'productos.stock_minimo')
            ->get();

        $pdf = Pdf::loadView('bajas_pdf', compact('productos'));
        return $pdf->stream('productos_bajas.pdf');
    }

    public function getBajas()
    {
        $productos = Producto::join('categorias', 'productos.id_categoria', '=', 'categorias.id_categoria')
            ->select(
                'productos.id_producto',
                'productos.nombre',
                'productos.stock',
                'productos.stock_minimo',
                'categorias.descripcion as categoria_nombre'
            )
            ->whereColumn('productos.stock', '<', 'productos.stock_minimo')
            ->get();

        return response()->json($productos);
    }

    // Obtener todos los productos
    public function getAll()
{
    $productos = Producto::with([
        'categoria',
        'medida',
        'ubicacion',
        'renglon' // 🔥 OBLIGATORIO
    ])->get();

    $data = $productos->map(function ($p) {
        return [
            'id_producto'   => $p->id_producto,
            'nombre'        => $p->nombre,
            'stock'         => $p->stock,
            'precio'        => $p->precio,

            // 🔥 ESTA LÍNEA ES LA CLAVE ABSOLUTA
            'renglon'       => $p->renglon ? $p->renglon->renglon : '',

            'categoria'     => $p->categoria ? $p->categoria->descripcion : '',
            'medida'        => $p->medida ? $p->medida->descripcion : '',
            'ubicacion'     => $p->ubicacion ? $p->ubicacion->descripcion : '',

            // IDs (se mantienen para edición)
            'id_renglon'    => $p->id_renglon,
            'id_categoria'  => $p->id_categoria,
            'id_medida'     => $p->id_medida,
            'id_ubicacion'  => $p->id_ubicacion,

            'marca'         => $p->marca,
            'detalle'       => $p->detalle,
            'estado'        => $p->estado,
            'usuario'       => $p->usuario
        ];
    });

    return response()->json($data);
}



    // Crear / actualizar producto
    public function store(Request $request)
    {
        try {
            Log::info('Datos recibidos en store:', $request->all());

            $validated = $request->validate([
                'id_renglon'    => 'required|exists:renglones,id_renglon',
                'id_categoria'  => 'required|exists:categorias,id_categoria',
                'id_medida'     => 'required|exists:medidas,id_medida',
                'id_ubicacion'  => 'required|exists:ubicaciones,id_ubicacion',
                'nombre'        => 'required|string',
                'marca'         => 'nullable|string|max:255',
                'detalle'       => 'nullable|string',
                'precio'        => 'required|numeric|min:0',
                'stock'         => 'required|integer|min:0',
                'estado'        => 'required|in:0,1',
                'usuario'       => 'required|string'
            ]);

            $productoData = $validated;

            // 🔥 CLAVE: no enviar la PK al crear
            unset($productoData['id_producto']);

            if ($request->filled('id_producto')) {
                $producto = Producto::findOrFail($request->id_producto);
                $producto->update($productoData);
                $message = 'Producto actualizado correctamente';
            } else {
                $producto = Producto::create($productoData);
                $message = 'Producto creado correctamente';
            }

            return response()->json([
                'success'  => true,
                'message'  => $message,
                'producto' => $producto
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error en ProductoController@store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto);
    }

    public function toggleEstado($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = $producto->estado == 1 ? 0 : 1;
        $producto->save();

        return response()->json(['estado' => $producto->estado]);
    }

    public function show($id)
    {
        $producto = Producto::with(['categoria', 'medida', 'ubicacion'])->findOrFail($id);
        return response()->json($producto);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(['success' => true]);
    }

    public function buscar(Request $request)
    {
        $query = $request->input('query');

        $productos = DB::table('productos')
            ->select('id_producto', 'nombre', 'precio')
            ->where('nombre', 'like', "%{$query}%")
            ->orWhere('id_producto', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($productos);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');

        $productos = Producto::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('id_producto', 'LIKE', "%{$query}%")
            ->select('id_producto', 'nombre', 'marca')
            ->limit(10)
            ->get();

        return response()->json($productos);
    }
}
