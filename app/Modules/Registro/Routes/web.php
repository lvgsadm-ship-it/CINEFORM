<?php
use Illuminate\Support\Facades\Route;
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

Route::prefix('registro')->group(function() {
    Route::get('/', 'RegistroController@index');
});

  Route::group(array('middleware' => array('auth', \Modules\Security\Http\Middleware\CheckSecurity::class)), function () {
        /* Route::get('/usuario/perfil/seleccionar', [Modules\Security\Http\Controllers\SecurityController::class, 'showProfileSelection'])->name('usuario.mostrarPerfilSeleccion');
        Route::get('/usuario/perfil/seleccionar/{id_rol}', [Modules\Security\Http\Controllers\SecurityController::class, 'seleccionarPerfil'])->name('usuario.seleccionarPerfil'); */
        Route::get('home', [Modules\Registro\Http\Controllers\RegistroController::class, 'home'])->name('home');
        /* Route::get('logout', [Modules\Security\Http\Controllers\SecurityController::class, 'logout'])->name('logout');
        Route::get('set-module/{id}', [Modules\Security\Http\Controllers\SecurityController::class, 'set_module'])->name('set_module');

        Route::match(['get', 'post'], 'update-profile', [Modules\Security\Http\Controllers\SecurityController::class, 'update_profile'])->name('update_profile');

        Route::get('show-avatar/{img}', [Modules\Security\Http\Controllers\SecurityController::class, 'show_avatar'])->name('show_avatar');

        Route::get('show-pdf/{pdf}/{page?}', [Modules\Security\Http\Controllers\SecurityController::class, 'show_pdf'])->name('show_pdf');

        Route::get('profiles', [Modules\Security\Http\Controllers\ProfilesController::class, 'index'])->name('profiles');
        Route::get('profiles-list', [Modules\Security\Http\Controllers\ProfilesController::class, 'list'])->name('profiles.list');
        Route::match(['get', 'post'], 'profiles-create', [Modules\Security\Http\Controllers\ProfilesController::class, 'create'])->name('profiles.create');
        Route::match(['get', 'post'], 'profiles-update/{id}', [Modules\Security\Http\Controllers\ProfilesController::class, 'update'])->name('profiles.update');
        Route::match(['get', 'post'], 'profiles-permissions/{id}', [Modules\Security\Http\Controllers\ProfilesController::class, 'permissions'])->name('profiles.permissions');

        //Route::get('users', [Modules\Security\Http\Controllers\UsersController::class, 'index'])->name('users');
        Route::get('users-list', [Modules\Security\Http\Controllers\UsersController::class, 'list'])->name('users.list');
        Route::match(['get', 'post'], 'users-create', [Modules\Security\Http\Controllers\UsersController::class, 'create'])->name('users.create');
        Route::match(['get', 'post'], 'users-update/{id}', [Modules\Security\Http\Controllers\UsersController::class, 'update'])->name('users.update');
        Route::match(['get', 'post'], 'users-password/{id}', [Modules\Security\Http\Controllers\UsersController::class, 'password'])->name('users.password');

        Route::match(['get', 'post', 'put'], 'file-admin/{id?}', [Modules\Security\Http\Controllers\SecurityController::class, 'file_admin'])->name('security.admin');


        Route::get('usuarios', [Modules\Security\Http\Controllers\UsersController::class, 'index'])->name('users'); */
    });