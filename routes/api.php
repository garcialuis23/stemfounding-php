<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Models\Project;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', function ($id) {
    return Project::find($id);
});
Route::post('/projects', [ProjectController::class, 'createProject']);

// Nueva ruta para obtener las inversiones de un proyecto por su ID
Route::get('/projects/{id}/investments', [ProjectController::class, 'getInvestments']);

// Ruta para eliminar un comentario por su índice
Route::delete('/projects/{id}/comments/{index}', [ProjectController::class, 'deleteComment2']);
Route::put('/projects/{id}/comments/{index}', [ProjectController::class, 'updateComment2']);

// Ruta para añadir un comentario
Route::post('/projects/{id}/comments', [ProjectController::class, 'addComment2']);

// Ruta para inactivar un proyecto  
Route::put('/projects/{id}/inactivate', [ProjectController::class, 'inactivateProject']);

// Ruta para actualizar un proyecto
Route::put('/projects/{id}', [ProjectController::class, 'updateProject']);

// Ruta para obtener los datos de un usuario por su ID
Route::get('/users/{id}', [UserController::class, 'show']);

