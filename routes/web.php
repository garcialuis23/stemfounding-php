<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/unlogged');
});

// Habilita las rutas de autenticación
Auth::routes(); 

Route::get('/home/{id?}', [HomeController::class, 'index'])->name('home');

// Rutas para proyectos de STEMFounding
Route::get('/newProject', [ProjectController::class, 'create'])->name('newProject');
Route::post('/newProject', [ProjectController::class, 'store'])->name('projects.store');
Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');

// Ruta para mostrar proyectos
Route::get('/unlogged', [ProjectController::class, 'unlogged'])->name('projects.unlogged');
Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

// Ruta para el perfil del usuario
Route::get('/profile', [HomeController::class, 'index'])->name('profile');
Route::get('/profile/{id}', [HomeController::class, 'show'])->name('home.show');

// Ruta para la página de aceptación del administrador
Route::get('/adminAccept', [ProjectController::class, 'adminAccept'])->name('adminAccept');

// Ruta para la página de banned del administrador
Route::get('/adminBandUser', [UserController::class, 'adminBandUser'])->name('adminBandUser');

// Ruta para actualizar el estado del proyecto
Route::put('/projects/{id}/updateStatus', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');

// Ruta para añadir comentarios a un proyecto
Route::post('/projects/{id}/addComment', [ProjectController::class, 'addComment'])->name('projects.addComment');

// Ruta para actualizar un comentario
Route::put('/projects/{projectId}/updateComment/{commentIndex}', [ProjectController::class, 'updateComment'])->name('projects.updateComment');

// Ruta para eliminar un comentario
Route::delete('/projects/{projectId}/deleteComment/{commentIndex}', [ProjectController::class, 'deleteComment'])->name('projects.deleteComment');

// Ruta para manejar inversiones en un proyecto
Route::post('/projects/{id}/invest', [ProjectController::class, 'invest'])->name('projects.invest');
Route::delete('/projects/withdrawInvestment/{id}', [ProjectController::class, 'withdrawInvestment'])->name('projects.withdrawInvestment');

// Ruta para actualizar el estado de los proyectos al llegar a la fecha límite
Route::get('/projects/updateProjectStatus', [ProjectController::class, 'checkAndRefundInvestments'])->name('projects.updateProjectStatus');
Route::get('/projects/checkAndRefundInvestments', [ProjectController::class, 'checkAndRefundInvestments'])->name('projects.checkAndRefundInvestments');

// Ruta para cambiar la contraseña
Route::get('/password/change', [UserController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/password/change', [UserController::class, 'changePassword'])->name('password.update');

// Ruta para banear/desbanear usuarios
Route::put('/user/{id}/ban', [UserController::class, 'banUser'])->name('user.ban');

// Rutas para depositar y retirar fondos
Route::post('/user/deposit', [UserController::class, 'deposit'])->name('deposit');
Route::post('/user/withdraw', [UserController::class, 'withdraw'])->name('withdraw');

// Ruta para cambiar el rol del usuario
Route::put('/user/{id}/changeRole', [UserController::class, 'changeRole'])->name('user.changeRole');

// Ruta para actualizar el IBAN del usuario
Route::put('/user/{id}/updateIBAN', [UserController::class, 'updateIBAN'])->name('user.updateIBAN');

// Ruta para actualizar el usuario
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');