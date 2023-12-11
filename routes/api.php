<?php

use App\Http\Controllers as BaseUrl;
use App\Http\Middleware\validateHospitalPermmision;
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
    // Route::get('/user', [BaseUrl\BaseControllers\AuthController::class, 'GetUser']);
    Route::controller(BaseUrl\BaseControllers\AuthController::class)->group(function () {
        Route::get('/users/currentUser', 'GetUser');
        Route::get('/users/{id}', 'GetUser');
        Route::get('/users', 'GetUsers');
        Route::patch('/users/updatePassword', 'ChangePassword');
        Route::delete('/users/{id}', 'Delete');
        Route::put('/users', 'UpdateUser');
        Route::post('/users/logout', 'Logout');
    });
    Route::controller(BaseUrl\AgentesController::class)->group(
        function () {
            Route::get('/agentes', 'index');
            Route::get('/agentes/getAgentesLiquidar', 'getAgentesLiquidar');
            Route::get('/agentes/getLiquidados', 'getLiquidados');
            Route::get('/agentes/getServicios', 'getServicios');
            Route::get('/agentes/getSectores', 'getSectores');
            Route::get('/agentes/{id}', 'AgenteById');
            Route::post('/agentes', 'store');
            Route::put('/agentes', 'update');
            Route::delete('/agentes/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\AgenfacController::class)->group(
        function () {
            Route::get('/agenfac/getExcel', 'generarExcel');
            Route::get('/agenfac/getPDF', 'generarPDF');
            Route::post('/agenfac/updateLiquidacion', 'updateAmount');
            Route::get('/agenfac/getperiodos', 'GetPeriodos');
            Route::get('/agenfac/getliquidados', 'GetLiquidados');
            Route::post('/agenfac/guardarLiquidacion', 'GuardarLiquidacion');
            Route::get('/agenfac', 'index');
            Route::post('/agenfac', 'store');
            Route::put('/agenfac', 'update');
            Route::delete('/agenfac/{id}', 'destroy');
            Route::get('/agenfac/{id}', 'facturacionById');
        }
    );
    Route::controller(BaseUrl\ComplementariaController::class)->group(
        function () {
            Route::get('/complementaria/getperiodos', 'GetPeriodos');
            Route::get('/complementaria/getPDF', 'GetPDF');
            Route::get('/complementaria/getLiquidadosComplementaria', 'GetLiquidadosComplementaria');
            Route::get('/complementaria/{id}', 'facturacionById');
            Route::post('/complementaria', 'store');
            Route::post('/complementaria/guardarLiquidacion', 'GuardarLiquidacion');
            Route::put('/complementaria', 'update');
            Route::delete('/complementaria/{id}', 'destroy');
            Route::get('/complementaria', 'index');
        }
    );
    Route::controller(BaseUrl\HospitalesController::class)->group(
        function () {
            Route::get('/hospitales', 'index')->middleware(validateHospitalPermmision::class);
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
            Route::put('/roles/user', 'updateRolUser');
        }
    );
    Route::controller(BaseUrl\PermissionsController::class)->group(
        function () {
            Route::get('/permissions', 'index');
            Route::get('/permissions/{id}', 'permissionsById');
            Route::post('/permissions', 'store');
            Route::put('/permissions', 'update');
            Route::delete('/permissions/{id}', 'destroy');
        }
    );
    Route::controller(BaseUrl\ProveedorsController::class)->group(
        function () {
            Route::get('/proveedores', 'GetPaginateResponse');
        }
    );
});
Route::controller(BaseUrl\BaseControllers\AuthController::class)->group(function () {
    Route::post('/users/register', 'Register');
    Route::post('/users/login', 'login');
});
