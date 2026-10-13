<?php

use App\Http\Controllers\FormularioController;
use Illuminate\Support\Facades\Route;

Route::get('/formulario', [FormularioController::class, 'mostrar'])->name('formulario.mostrar');
Route::post('/formulario', [FormularioController::class, 'recibir'])->name('formulario.recibir');
Route::get('/formulario/listado', [FormularioController::class, 'listar'])->name('formulario.listar');
Route::get('/formulario/{dato}/editar', [FormularioController::class, 'editar'])->name('formulario.editar');
Route::put('/formulario/{dato}', [FormularioController::class, 'actualizar'])->name('formulario.actualizar');
Route::delete('/formulario/{dato}', [FormularioController::class, 'eliminar'])->name('formulario.eliminar');
