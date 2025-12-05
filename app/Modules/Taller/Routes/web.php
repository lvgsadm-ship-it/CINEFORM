<?php

use Illuminate\Support\Facades\Route;
use Modules\Taller\Http\Controllers\InscripcionController;
use Modules\Taller\Http\Controllers\CursoInscritoController;
use Modules\Taller\Http\Controllers\CursoAsignadoController;
use Modules\Taller\Http\Controllers\CursoDetalleController;
use Modules\Taller\Http\Controllers\CursoController;
use Modules\Taller\Http\Controllers\EditarCursoController;
use Modules\Taller\Http\Controllers\BaseController;
use Modules\Comun\Http\Controllers\PersonalDataController;

Route::prefix('taller')->group(function() {
    // Ruta para cursos asignados (facilitador)
    Route::get('/Cursos-asignados', [CursoAsignadoController::class, 'index'])
        ->name('taller.mis-cursos-asignados');
        
    // Ruta para ver detalle de un curso
    Route::get('/cursos/{curso}', [CursoDetalleController::class, 'show'])
        ->name('taller.cursos.show');
        
    // Ruta para cursos inscritos (participante)
    Route::get('/mis-cursos', [CursoInscritoController::class, 'index'])
        ->name('taller.mis-cursos');
    
    // Rutas de gestión de cursos
    Route::middleware(['auth'])->group(function () {
        // Ruta principal de cursos (listado)
        Route::get('/cursos', [CursoController::class, 'index'])
            ->name('taller.cursos.index');
        
        // Ruta para mostrar formulario de edición
        Route::get('/cursos-asignados/{curso}/editar', [EditarCursoController::class, 'edit'])
            ->name('taller.cursos.edit');
        
        // Ruta para actualizar un curso
        Route::put('/cursos-asignados/{curso}', [EditarCursoController::class, 'update'])
            ->name('taller.cursos.update');
        
        // Ruta para aceptar un curso (cambiar a estado Aceptado)
        Route::post('/cursos/{curso}/aceptar', [CursoAsignadoController::class, 'aceptar'])
            ->name('taller.cursos.aceptar');
            
        // Ruta para aceptar un curso y actualizar su estado a 6 (Aceptado)
        Route::post('/cursos/{curso}/aceptar-estado', [CursoAsignadoController::class, 'aceptarCurso'])
            ->name('taller.cursos.aceptar-estado');
        
        // Rutas de inscripciones
        Route::post('/inscripciones', [InscripcionController::class, 'store'])
            ->name('taller.inscripciones.store');

        
        Route::delete('/inscripciones/{inscripcion}', [InscripcionController::class, 'destroy'])
            ->name('taller.inscripciones.destroy');

        Route::get('/prueba', [PersonalDataController::class, 'DatosPersonales'])
            ->name('taller.Prueba');

        // Ruta para actualizar el estado del curso
Route::put('/taller/cursos/{curso}/status', [CursoController::class, 'updateStatus'])
    ->name('taller.cursos.updateStatus');
    });
});
