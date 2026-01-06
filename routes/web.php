<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('login');
});

//Ruta para iniciar sesion

Route::get('/login', [UserController::class, 'loginView'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');

//Route::get('/app', [UserController::class, 'appv'])->name('app');
Route::get('/app', [UserController::class, 'appView'])->name('app');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

//Ruta para Ingresar al Panel Admin
Route::get('/app', [UserController::class, 'appView'])->name('app');
