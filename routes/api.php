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
        Route::get('/allUsers', [UserController::class, 'getAllUsers']);
        Route::get('/usuarioPerfil', [UserController::class, 'getUsuarioPerfil']);
        Route::get('/users/doctors', [UserController::class, 'getDoctors']);
        Route::post('/usuarios/changeEmail', [UserController::class, 'changeEmail']);
        Route::post('/users/validateCurrentPassword', [UserController::class, 'validateCurrentPassword']);
        Route::post('/users/uploadProfile',  [UserController::class, 'uploadProfile']);
        Route::post('/users/changePassword', [UserController::class, 'changePassword']);
        Route::post('/users/registerDoc', [UserController::class, 'registerDoctor']);
        Route::post('/users/registerAdmin', [UserController::class, 'registerAdmin']);
        Route::post('/users/registerSec', [UserController::class, 'registerSec']);

        Route::put('/usuarios/changeNumero', [UserController::class, 'changeNumero']);
        Route::put('/usuarios/changeUsername', [UserController::class, 'changeUsername']);
        Route::put('/users/nickname', [UserController::class, 'changeNickname']);

        Route::get('/relatorios', [RelatorioController::class, 'getRelatorios']);
        Route::post('/relatorios', [RelatorioController::class, 'createRelatorio']);
        Route::get('/relatorios/usuario', [RelatorioController::class, 'getRelatoriosByUser']);
        Route::get('/relatorios/download/{id}', [FileController::class, 'getFileDownload']);
        Route::get('/relatorios/{relatorio_id}/{atrelado_a}', [RelatorioController::class, 'changeViewed']);
        Route::get('/relatorios/exportar', [FileController::class, 'exportarRelatorios']);

        Route::get('/contatos', [UserController::class, 'getUsersByType']);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/loggout', [AuthController::class, 'logout']);

    Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
    Route::post('/resetPassword/checkToken', [AuthController::class, 'checkToken']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
});
