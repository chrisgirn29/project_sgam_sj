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
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\CdpController;


Route::get('/login', [UserController::class, 'loginView'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');


Route::middleware('auth')->group(function () {
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
Route::get('/lista/view/proceso/requisciones', function () {
    return view('viewlist');
});
//Rutas para ver reportes de Solicitud, Requisicon, Prepedido, Autorización
Route::get('/view/oficio/reports', function () {
    return view('reports/oficio');
});
//Ruta para abrir la vista Proveedores
Route::get('/view/form/suppliers', function () {
    return view('viewsupplier');
});

//Ruta para abrir la vista Usuarios
Route::get('/view/form/users', function () {
    return view('viewusers');
});

//Ruta para abrir la vista de Roles
Route::get('/view/form/rols', function () {
    return view('viewroles');
});
//Ruta para abrir la vista de Empresa
Route::get('/view/company', function () {
    return view('viewempresa');
});
//Ruta para ver la Requisicones a Recepcion
Route::get('/view/recepcion', function () {
    return view('viewrecepcion');
});
//Ruta para ver las requisciones a entrega
Route::get('/view/despacho', function () {
    return view('viewentrega');
});
//RUTA PARA VER LAS KARDEX
Route::get('/view/detail/kardex', function () {
    return view('viewkardex');
});
//RUTA PARA VER EL DETALLE DE LAS RECEPCIONES EMITIDAS
Route::get('/view/detail/recepciones/vista', function () {
    return view('viewdetailrecepciones');
});
//RUTA PARA VER UNA RECEPCION EN ESPECIFICO
Route::get('/view/detail/unique/recepcion', function () {
    return view('reports.recepcion');
});
//RUTA PARA VER EL DETALLE DE LAS ENTERGAS EMITIDAS
Route::get('/view/detail/entregas/vista', function () {
    return view('viewdetailentregas');
});

//RUTA PARA VER EL DETALLE DE Las REQUISICIONES
Route::get('/detail/requisiciones/view', function () {
    return view('viewrequisicionlist');
});
//RUTA PARA CREAR CDP
Route::get('/create/cdp/view', function () {
    return view('cdp');
});
//RUTA PARA LISTAR LOS CDP
Route::get('/view/detail/cdp/view', function () {
    return view('reports.viewcdp');
});

//---------------------------------Rutas para otros procedimientos
//Ruta para iniciar sesion



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

Route::get('/requisiciones/listado', [RequisicionController::class, 'getListadoss'])
    ->name('requisiciones.getListadoss');

    Route::get('/requisiciones/proceso', [RequisicionController::class, 'getListado'])
    ->name('requisiciones.getListado');

Route::get('/requisiciones/listado', [RequisicionController::class, 'getListados'])
    ->name('requisiciones.getListados');

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


/* =========================
   PROVEEDORES
========================= */
Route::get('/proveedores', [ProveedorController::class, 'index'])
    ->name('proveedores.index');

Route::get('/proveedores/getAll', [ProveedorController::class, 'getAll'])
    ->name('proveedores.getAll');

Route::post('/proveedores', [ProveedorController::class, 'store'])
    ->name('proveedores.store');

Route::put('/proveedores/{id}', [ProveedorController::class, 'update']);

Route::put('/proveedores/{id}/toggle-estado', [ProveedorController::class, 'toggleEstado']);

//Ruta para Users
Route::middleware('auth')->group(function () {
    Route::get('/usuarios', [UserController::class, 'viewUsers']);
    Route::get('/usuarios/all', [UserController::class, 'all'])->name('users.all');
    Route::post('/usuarios', [UserController::class, 'store']);
    Route::put('/usuarios/{id}/estado', [UserController::class, 'updateEstado']);
Route::put('/usuarios/{id}', [UserController::class, 'update']);
});

//Rutas para Roles
Route::middleware('auth')->group(function () {

    Route::get('/roles', [RolController::class, 'index'])
        ->name('roles.index');

    Route::post('/roles', [RolController::class, 'store'])
        ->name('roles.store');

    Route::put('/roles/{id}', [RolController::class, 'update'])
        ->name('roles.update');

    Route::put('/roles/{id}/estado', [RolController::class, 'cambiarEstado'])
        ->name('roles.estado');

});
// Roles (JSON para selects)
Route::middleware('auth')->get('/roles/all', [RolController::class, 'all'])
    ->name('roles.all');

    //Ruta para modificar los datos de la empresa
Route::put('/empresa/{id}', [EmpresaController::class, 'update'])->name('empresa.update');
// Carga la vista
Route::get('/empresa', function(){
    return view('empresa'); // Blade: resources/views/empresa.blade.php
})->name('empresa.view');

// Devuelve los datos de la empresa en JSON
Route::get('/empresa/data', [EmpresaController::class, 'index'])->name('empresa.data');
// web.php
Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.data');
Route::post('/empresa/update', [EmpresaController::class, 'update'])->name('empresa.update');
Route::put('empresa', [EmpresaController::class, 'update'])->name('empresa.update');
Route::get('/empresa/data', [EmpresaController::class, 'index'])->name('empresa.data');
Route::post('/empresa/update', [EmpresaController::class, 'update'])->name('empresa.update');




// web.php
Route::get('/requisiciones/{id}/recepcionar',
    [RequisicionController::class, 'recepcionar']
)->name('requisiciones.recepcionar');


Route::post('/recepciones', [RecepcionController::class, 'store'])
    ->name('recepciones.store');

Route::post('/recepciones/store', [RecepcionController::class, 'store'])
    ->name('recepciones.store');


    Route::get('/requisiciones/{id}/recepcionar', [RecepcionController::class, 'recepcionar'])
    ->name('requisiciones.recepcionar');

Route::post('/recepciones', [RecepcionController::class, 'store'])
    ->name('recepciones.store');
    Route::get('/requisiciones', [RequisicionController::class, 'index'])
    ->name('requisiciones.index');


    Route::get(
    '/requisiciones/{id}/entregar',
    [RequisicionController::class, 'entregar']
)->name('requisiciones.entregar');

Route::get(
    '/requisiciones/{id}/detalle',
    [RequisicionController::class, 'getDetalle']
);
Route::get('/requisiciones/{id}/despacho',
    [RequisicionController::class, 'despacho']
)->name('requisiciones.despacho');



Route::post('/entregas',
    [EntregaController::class, 'store']
)->name('entregas.store');


Route::get('/kardex/{id_producto}',
    [ProductoController::class, 'kardex']
)->name('kardex.view');

Route::get('/kardex/imprimir/{id_producto}',
    [ProductoController::class, 'imprimirKardex']
)->name('kardex.pdf');
Route::get('/recepciones/listado', [RecepcionController::class, 'getListadoRecepciones'])
    ->name('recepciones.getListado');


    Route::get(
    '/impresion/recepcion/{id}',
    [RecepcionController::class, 'imprimirRecepcion']
)->name('impresion.recepcion');

Route::get(
    '/impresion/recepcion/{idRecepcion}/{idRequisicion}',
    [RecepcionController::class, 'verPdf']
)->name('recepcion.pdf');


Route::get('/entregas/listado', [EntregaController::class, 'getListadoEntregas'])
    ->name('entregas.getListado');

    Route::get(
    '/impresion/entrega/{idEntrega}/{idRequisicion}',
    [EntregaController::class, 'imprimirEntrega']
)->name('entregas.imprimir');

Route::get('/requisiciones/{id}/no-existencia',
    [RequisicionController::class, 'verNoexistencia']
)->name('requisiciones.noexistencia');

Route::get('/cdp/crear', [CdpController::class, 'create'])
    ->name('cdp.create');

   //Ruta para poder mandar a insertar los datos de la vista de la cdp
   Route::post('/cdp/guardar', [CdpController::class, 'store'])->name('cdp.store');
   Route::get('/cdp/listado', [CdpController::class, 'getListadoCdp'])
    ->name('cdp.getListado');
    Route::get('/cdp/{id}/pdf', [CdpController::class, 'pdfCdp'])
    ->name('cdp.pdf');
});
