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
    Route::controller(BaseUrl\AgentesController::class)->group(
        function () {
            Route::get('/agentes', 'index');
            Route::get('/agentes/{id}', 'AgenteById');
            Route::post('/agentes', 'store');
            Route::put('/agentes', 'update');
            Route::delete('/agentes/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\AgenfacController::class)->group(
        function () {
            Route::post('/agenfac/updateLiquidacion', 'updateAmount');
            Route::get('/agenfac', 'index');
            Route::get('/agenfac/{id}', 'facturacionById');
            Route::post('/agenfac', 'store');
            Route::put('/agenfac', 'update');
            Route::delete('/agenfac/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\ComplementariaController::class)->group(
        function () {
            Route::get('/complementaria', 'index');
            Route::get('/complementaria/{id}', 'facturacionById');
            Route::post('/complementaria', 'store');
            Route::put('/complementaria', 'update');
            Route::delete('/complementaria/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\HospitalesController::class)->group(
        function () {
            Route::get('/hospitales', 'index');
            Route::get('/hospitales/{id}', 'hospitalByIdResponse');
            Route::post('/hospitales', 'store');
            Route::put('/hospitales', 'update');
            Route::delete('/hospitales/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\IncisosController::class)->group(
        function () {
            Route::get('/incisos', 'index');
            Route::get('/incisos/{id}', 'incisoById');
            Route::post('/incisos', 'store');
            Route::put('/incisos', 'update');
            Route::delete('/incisos/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\SectorController::class)->group(
        function () {
            Route::get('/sector', 'index');
            Route::get('/sector/{id}', 'sectorById');
            Route::post('/sector', 'store');
            Route::put('/sector', 'update');
            Route::delete('/sector/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\ServicioController::class)->group(
        function () {
            Route::get('/servicio', 'index');
            Route::get('/servicio/{id}', 'servicioById');
            Route::post('/servicio', 'store');
            Route::put('/servicio', 'update');
            Route::delete('/servicio/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\ServicioController::class)->group(
        function () {
            Route::get('/servicio', 'index');
            Route::get('/servicio/{id}', 'servicioById');
            Route::post('/servicio', 'store');
            Route::put('/servicio', 'update');
            Route::delete('/servicio/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\RolesController::class)->group(
        function () {
            Route::get('/roles', 'index');
            Route::get('/roles/{id}', 'rolesById');
            Route::post('/roles', 'store');
            Route::put('/roles', 'update');
            Route::delete('/roles/{id}', 'destroy');
        }
    );
});
Route::controller(BaseUrl\BaseControllers\AuthController::class)->group(function () {
    Route::post('/user/register', 'Register');
    Route::post('/user/login', 'login');
});
