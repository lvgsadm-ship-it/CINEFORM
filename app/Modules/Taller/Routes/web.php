<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 
use \Modules\Comun\Http\Controllers\PersonalDataController;

Route::prefix('taller')->group(function() {
    Route::get('/', 'TallerController@index');
    Route::get('/cursos/{id}', 'TallerController@cursoDetalle')->name('taller.cursos.show');
    Route::get('/prueba', [\Modules\Comun\Http\Controllers\PersonalDataController::class, 'prueba']);
});
