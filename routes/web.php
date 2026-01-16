<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\RenglonController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\RequisicionController;
use App\Http\Controllers\RequisicionDetalleController;

Route::get('/', function () {
    return view('login');
});

//----------------------------------Rutas para Entrar a la Vista
//Ruta para la vista de categorias
Route::get('/view/catergories', function () {
    return view('viewcategories');
});

//Ruta para la vista de Programas
Route::get('/view/programs', function () {
    return view('viewprograms');
});
//cargar la vista de medidas
Route::get('/medidas/view', function () {
    return view('viewmedidas');
});
Route::get('/view/roles/see', function () {
    return view('viewrenglones');
});

Route::get('/products/view/createpro', function () {
    return view('viewproducts');
});
Route::get('/ubicaciones/u/see', function () {
    return view('viewubicaciones');
});
Route::get('/see/employes', function () {
    return view('viewemployes');
});
Route::get('/view/see/req', function () {
    return view('viewrequesiciones');
});
Route::get('/view/procedure', function () {
    return view('viewprocedure');
});
Route::get('/view/listado/requisiciones', function () {
    return view('viewlist');
});
//Rutas para ver reportes de Solicitud, Requisicon, Prepedido, Autorización
Route::get('/view/oficio/reports', function () {
    return view('reports/oficio');
});


//---------------------------------Rutas para otros procedimientos
//Ruta para iniciar sesion

Route::get('/login', [UserController::class, 'loginView'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');

//Route::get('/app', [UserController::class, 'appv'])->name('app');
Route::get('/app', [UserController::class, 'appView'])->name('app');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

//Ruta para Ingresar al Panel Admin
Route::get('/app', [UserController::class, 'appView'])->name('app');

//Rutas para la tabla Categorias
Route::get('/categorias/getAll', [CategoriaController::class, 'getAll'])->name('categorias.getAll');
Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
Route::patch('/categorias/toggle-estado/{id}', [CategoriaController::class, 'toggleEstado'])->name('categorias.toggle');

//Ruta para la Tabla Programas
Route::get('/programas/all', [ProgramasController::class, 'getAll'])->name('programas.getAll');
Route::post('/programas', [ProgramasController::class, 'store'])->name('programas.store');
Route::put('/programas/{id}', [ProgramasController::class, 'update']);
Route::patch('/programas/toggle-estado/{id}',[ProgramasController::class, 'toggleEstado']);
Route::get('/programas/filtrar-por-anio/{anio}',[ProgramasController::class, 'filtrarPorAnio'])->name('programas.filtrarPorAnio');

//Rutas para la tabla Medidas
Route::get('/medidas/getAll', [MedidaController::class, 'getAll'])->name('medidas.getAll');
Route::post('/medidas', [MedidaController::class, 'store'])->name('medidas.store');
Route::put('/medidas/{id}', [MedidaController::class, 'update'])->name('medidas.update');
Route::patch('/medidas/toggle-estado/{id}', [MedidaController::class, 'toggleEstado'])->name('medidas.toggle');

//R
Route::get('/renglones/get-all', [RenglonController::class, 'getAll'])->name('renglones.getAll');
Route::post('/renglones', [RenglonController::class, 'store'])->name('renglones.store');
Route::put('/renglones/{id}', [RenglonController::class, 'update']);
Route::patch('/renglones/toggle-estado/{id}', [RenglonController::class, 'toggleEstado']);

Route::prefix('productos')->group(function () {

    Route::get('/get-all', [ProductoController::class, 'getAll'])
        ->name('productos.getAll');

    Route::post('/store', [ProductoController::class, 'store'])
        ->name('productos.store');

    Route::get('/{id}', [ProductoController::class, 'show'])
        ->name('productos.show');

    Route::put('/{id}', [ProductoController::class, 'update'])
        ->name('productos.update');

    Route::delete('/{id}', [ProductoController::class, 'destroy'])
        ->name('productos.destroy');

    Route::patch('/toggle-estado/{id}', [ProductoController::class, 'toggleEstado'])
        ->name('productos.toggleEstado');

    // Vista de bajas
    Route::get('/bajas', [ProductoController::class, 'index'])
        ->name('productos.bajas');

    // Productos con stock bajo
    Route::get('/get-bajas', [ProductoController::class, 'getBajas'])
        ->name('productos.getBajas');

});


//Rutas para la tabla Ubicaciones
Route::get('/getAll', [UbicacionController::class, 'getAll'])->name('ubicaciones.getAll');
Route::post('/store', [UbicacionController::class, 'store'])->name('ubicaciones.store');
Route::post('ubicaciones/store', [UbicacionController::class, 'store'])->name('ubicaciones.store');
Route::put('/ubicaciones/{id}', [UbicacionController::class, 'update']);
Route::patch('/toggle-estado/{id}', [UbicacionController::class, 'toggleEstado'])->name('ubicaciones.toggleEstado');


//Rutas para la tabla ubicaciones

//cargar la vista de Ubicaciones
Route::get('/ubicaciones/view', function () {
    return view('viewubicaciones');
});
//Rutas para la tabla Ubicaciones
Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
Route::get('/ubicaciones/all', [UbicacionController::class, 'getAll'])->name('ubicaciones.getAll');
Route::post('/ubicaciones', [UbicacionController::class, 'store'])->name('ubicaciones.store');
Route::put('/ubicaciones/{id}', [UbicacionController::class, 'update']);
Route::patch('/toggle-estado/{id}', [UbicacionController::class, 'toggleEstado']);
Route::get('/productos/buscar', [ProductoController::class, 'buscar']);


//Ruta para editar la tabla empleados
Route::get('/empleados', [EmpleadoController::class, 'index'])
    ->name('empleados.index');

/* Obtener todos (DataTable) */
Route::get('/empleados/get-all', [EmpleadoController::class, 'getAll'])
    ->name('empleados.getAll');

/* Registrar */
Route::post('/empleados', [EmpleadoController::class, 'store'])
    ->name('empleados.store');

/* Actualizar */
Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])
    ->name('empleados.update');

/* Cambiar estado (activo/inactivo) */

Route::put('/empleados/{id}/toggle-estado', [EmpleadoController::class, 'toggleEstado']);
Route::get('/requisiciones/ajax/empleados', [RequisicionController::class, 'ajaxEmpleados']);
Route::get('/requisiciones/ajax/programas', [RequisicionController::class, 'ajaxProgramas']);
Route::post('/requisiciones', [RequisicionController::class, 'store'])
    ->name('requisiciones.store');
Route::get('/requisiciones/ajax/empleado/{id}', [RequisicionController::class, 'empleadoDetalle']);

Route::get('/requisiciones/ajax/productos', [RequisicionController::class, 'productos']);

Route::get('/requisiciones/create', [RequisicionController::class, 'create'])
     ->name('requisiciones.create');

     Route::post('/requisiciones', [RequisicionController::class, 'store'])
    ->name('requisiciones.store');
Route::post('/requisiciones/detalles',
    [RequisicionDetalleController::class, 'store']
)->name('requisiciones.detalles.store');

Route::get('/requisiciones/emitidas',
    [RequisicionController::class, 'getSolicitudesEmitidas']
)->name('requisiciones.emitidas');

Route::get('/requisiciones/listado', [RequisicionController::class, 'getListado'])
    ->name('requisiciones.getListado');


Route::get('/requisiciones/{id}/expediente',
    [RequisicionController::class, 'expediente']
)->name('requisiciones.expediente');

Route::get('/solicitud/pdf/{id}',
    [RequisicionController::class, 'verPdf']
)->name('solicitud.pdf');

Route::get('/requisiciones/{id}/requisicion/pdf',
    [RequisicionController::class, 'pdf']
)->name('requisiciones.requisicion.pdf');




Route::get('/autorizacion/pdf/{id}', [RequisicionController::class, 'verAutorizacion'])
    ->name('autorizacion.ver');

    Route::get('/requisiciones/{id}/prepedido/pdf',
    [RequisicionController::class, 'prepedidoPdf']
)->name('requisiciones.prepedido.pdf');
