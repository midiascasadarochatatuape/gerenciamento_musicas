<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Grupo de rotas da API de Louvor
Route::prefix('v1')->group(function () {

    // Endpoint de informações da API
    Route::get('/info', [ApiController::class, 'info']);

    // Rotas de Escalas
    Route::get('/schedules', [ApiController::class, 'getSchedules']);
    Route::get('/schedules/{id}', [ApiController::class, 'getSchedule']);

    // Rotas de Grupos
    Route::get('/groups', [ApiController::class, 'getGroups']);
    Route::get('/groups/{id}', [ApiController::class, 'getGroup']);

    // Rotas de Músicas
    Route::get('/songs', [ApiController::class, 'getSongs']);
    Route::get('/songs/{id}', [ApiController::class, 'getSong']);

});

// Rotas sem versionamento (compatibilidade)
Route::get('/info', [ApiController::class, 'info']);
Route::get('/schedules', [ApiController::class, 'getSchedules']);
Route::get('/schedules/{id}', [ApiController::class, 'getSchedule']);
Route::get('/groups', [ApiController::class, 'getGroups']);
Route::get('/groups/{id}', [ApiController::class, 'getGroup']);
Route::get('/songs', [ApiController::class, 'getSongs']);
Route::get('/songs/{id}', [ApiController::class, 'getSong']);
