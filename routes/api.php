<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'cors'], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/validaSessao', [AuthController::class, 'isLogged']);
        Route::get('/usuarios', [UserController::class, 'getUsuarios']);
        Route::get('/relatorios', [RelatorioController::class, 'getRelatorios']);
        Route::post('/relatorios', [RelatorioController::class, 'createRelatorio']);
        Route::get('/relatorios/download/{id}', [FileController::class, 'getFileDownload']);
        Route::get('/relatorios/{relatorio_id}/{atrelado_a}', [RelatorioController::class, 'changeViewed']);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/loggout', [AuthController::class, 'logout']);

    Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::post('/resetPassword/checkToken', [AuthController::class, 'checkToken']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
});
