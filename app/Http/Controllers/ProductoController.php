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
