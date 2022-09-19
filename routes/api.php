<?php

use App\Http\Controllers as BaseUrl;
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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [BaseUrl\BaseControllers\AuthController::class, 'GetUser']);
    Route::controller(BaseUrl\AgentesController::class)->group(function () {
        Route::get('/agentes', 'index');
        Route::get('/agentes/{id}', 'AgenteByIdResponse');
        Route::post('/agentes', 'store');
        Route::put('/agentes', 'update');
        Route::delete('/agentes/{id}', 'destroy');
    });
    Route::controller(BaseUrl\AgenfacController::class)->group(function () {
        Route::post('/agenfac/updateLiquidacion', 'updateAmount');
        Route::get('/agenfac', 'index');
        Route::get('/agenfac/{id}', 'facturacionById');
        Route::post('/agenfac', 'store');
        Route::put('/agenfac', 'update');
        Route::delete('/agenfac/{id}', 'destroy');
    });
});
Route::controller(BaseUrl\BaseControllers\AuthController::class)->group(function () {
    Route::post('/register', 'Register');
    Route::post('/login', 'login');
});
