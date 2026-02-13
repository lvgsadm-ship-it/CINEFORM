<?php
use Illuminate\Support\Facades\Route;

Route::prefix('participante')->group(function () {
    
    Route::group(array('middleware' => array('auth', '\Modules\Security\Http\Middleware\CheckSecurity::class')), function () {
        
        // 🏠 DASHBOARD PRINCIPAL
        Route::get('/', [Modules\Participante\Http\Controllers\DashboardController::class, 'index'])->name('participante.dashboard');
        
        // 📋 SOLICITUD TALLER
        Route::get('/solicitar-taller', [Modules\Participante\Http\Controllers\SolicitudTallerController::class, 'index'])->name('participante.solicitar-taller');
        Route::post('/solicitar-taller', [Modules\Participante\Http\Controllers\SolicitudTallerController::class, 'store']);
        
        // 📚 GESTIÓN CURSOS
        Route::get('/cursos-oferta', [Modules\Participante\Http\Controllers\GestionCursosController::class, 'oferta'])->name('participante.cursos.oferta');
        Route::post('/cursos-inscribirse/{curso}', [Modules\Participante\Http\Controllers\GestionCursosController::class, 'inscribirse']);
        Route::get('/cursos-mis-inscripciones', [Modules\Participante\Http\Controllers\GestionCursosController::class, 'misInscripciones'])->name('participante.cursos.inscripciones');
        
        // 📝 GESTIÓN EVALUACIONES
        Route::get('/evaluaciones', [Modules\Participante\Http\Controllers\GestionEvaluacionesController::class, 'index'])->name('participante.evaluaciones');
        Route::get('/evaluaciones/{id}/aplicar', [Modules\Participante\Http\Controllers\GestionEvaluacionesController::class, 'aplicar']);
        Route::get('/certificados', [Modules\Participante\Http\Controllers\GestionEvaluacionesController::class, 'certificados'])->name('participante.certificados');
    });
});
