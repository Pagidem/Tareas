<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareasController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tareas', [TareasController::class,'index']);
Route::post('/nuevatarea', [TareasController::class,'store']);
Route::put('/cambiarEstado/{id}/{estado}', [TareasController::class,'cambiarestado']);
Route::put('/editarTarea/{id}', [TareasController::class,'update']);


