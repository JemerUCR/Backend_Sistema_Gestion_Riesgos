<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::middleware(['auth:sanctum', CheckRole::class . ':Administrador,Encargador,Supervisor'])->group(function () {
    Route::put('/usuario/{cedula}', [UsuarioController::class, 'update']);
    Route::get('usuario/{cedula}', [UsuarioController::class, 'show']);
    Route::delete('usuario/{cedula}', [UsuarioController::class, 'destroy']);
});

Route::post('/usuario', [UsuarioController::class, 'store']);
Route::get('/usuario', [UsuarioController::class, 'index']);

Route::post('login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
