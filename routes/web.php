<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\MedidaController;

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
Route::get('/programas/all', [ProgramasController::class, 'getAll'])
    ->name('programas.getAll');

Route::post('/programas', [ProgramasController::class, 'store'])
    ->name('programas.store');

Route::put('/programas/{id}', [ProgramasController::class, 'update']);
Route::patch('/programas/toggle-estado/{id}',
    [ProgramasController::class, 'toggleEstado']);

Route::get('/programas/filtrar-por-anio/{anio}',
    [ProgramasController::class, 'filtrarPorAnio']
)->name('programas.filtrarPorAnio');

//Rutas para la tabla Medidas
Route::get('/medidas/getAll', [MedidaController::class, 'getAll'])->name('medidas.getAll');
Route::post('/medidas', [MedidaController::class, 'store'])->name('medidas.store');
Route::put('/medidas/{id}', [MedidaController::class, 'update'])->name('medidas.update');
Route::patch('/medidas/toggle-estado/{id}', [MedidaController::class, 'toggleEstado'])->name('medidas.toggle');
