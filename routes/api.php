<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\PlatformController;

// Listar todas las plataformas
Route::get('/platforms', [PlatformController::class, 'listPlatform']);

// Crear una nueva plataforma
Route::post('/platforms', [PlatformController::class, 'savePlatform']);

// Actualizar una plataforma
Route::put('/platforms/{id}', [PlatformController::class, 'updatePlataform']);

// Eliminar una plataforma
Route::delete('/platforms/{id}', [PlatformController::class, 'destroyPlataform']);

// Listar todos los Games
Route::get('/games', [GameController::class, 'listGame']);

// filtrar  games por el slug
Route::get('/games/{slug}', [GameController::class, 'getGameBySlug']);

// Crear un nuevo Game
Route::post('/games', [GameController::class, 'saveGame']);

// Actualizar una Game
Route::put('/games/{id}', [GameController::class, 'updateGame']);

// Eliminar una Game
Route::delete('/games/{id}', [GameController::class, 'destroyGame']);





// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function () {
//      Rutas protegidas aquí
// });
