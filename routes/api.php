<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreanceRelanceController;
use App\Http\Controllers\EtapeRelanceController;
use App\Http\Controllers\EventRelanceController;
use App\Http\Controllers\RelanceDossierController;
use App\Http\Controllers\ReleveController;
use App\Http\Controllers\SituationRelanceController;
use App\Http\Controllers\StatutRelanceController;
use App\Http\Controllers\StatutRelanceDetailController;
use App\Http\Controllers\SousModeleController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);
    Route::post('/', [ClientController::class, 'store']);
    Route::get('{id}', [ClientController::class, 'show']);
    Route::put('{id}', [ClientController::class, 'update']);
    Route::patch('{id}', [ClientController::class, 'update']);
    Route::delete('{id}', [ClientController::class, 'destroy']);
});

Route::prefix('releves')->group(function () {
    Route::get('/', [ReleveController::class, 'index']);
    Route::post('/', [ReleveController::class, 'store']);
    Route::get('{id}', [ReleveController::class, 'show']);
    Route::put('{id}', [ReleveController::class, 'update']);
    Route::patch('{id}', [ReleveController::class, 'update']);
    Route::delete('{id}', [ReleveController::class, 'destroy']);
    
    // 🔁 Pour les SoftDeletes
    Route::get('trashed', [ReleveController::class, 'trashed']);
    Route::post('{id}/restore', [ReleveController::class, 'restore']);
});

Route::prefix('etape-relances')->group(function () {
    Route::get('/', [EtapeRelanceController::class, 'index']);
    //Route::post('/', [EtapeRelanceController::class, 'store']);
    Route::get('{id}', [EtapeRelanceController::class, 'show']);
    Route::put('{id}', [EtapeRelanceController::class, 'update']);
    Route::patch('{id}', [EtapeRelanceController::class, 'update']);
    Route::delete('{id}', [EtapeRelanceController::class, 'destroy']);
    Route::get('{id}/download', [EtapeRelanceController::class, 'downloadPdf']);
});

Route::prefix('creance-relances')->group(function () {
    Route::get('/', [CreanceRelanceController::class, 'index']);
    Route::post('/', [CreanceRelanceController::class, 'store']);
    Route::get('{id}', [CreanceRelanceController::class, 'show']);
    Route::put('{id}', [CreanceRelanceController::class, 'update']);
    Route::patch('{id}', [CreanceRelanceController::class, 'update']);
    Route::delete('{id}', [CreanceRelanceController::class, 'destroy']);
});

Route::prefix('relance-dossiers')->group(function () {
    Route::get('/', [RelanceDossierController::class, 'index']);
    Route::get('{id}', [RelanceDossierController::class, 'show']);
    Route::post('/', [RelanceDossierController::class, 'store']);
    Route::put('{id}', [RelanceDossierController::class, 'update']);
    Route::delete('{id}', [RelanceDossierController::class, 'destroy']);
    Route::post('{numero_relance_dossier}/etape-relances', [EtapeRelanceController::class, 'store']);
    // Relances - changement de statut
        Route::patch('/{numero_relance_dossier}/status', [RelanceDossierController::class, 'updateStatus']);
});

Route::prefix('situation-relances')->group(function () {
    Route::get('/', [SituationRelanceController::class, 'index']);
    Route::post('/', [SituationRelanceController::class, 'store']);
    Route::get('{id}', [SituationRelanceController::class, 'show']);
    Route::put('{id}', [SituationRelanceController::class, 'update']);
    Route::patch('{id}', [SituationRelanceController::class, 'update']);
    Route::delete('{id}', [SituationRelanceController::class, 'destroy']);
});

Route::prefix('statut-relances')->group(function () {
    Route::get('/', [StatutRelanceController::class, 'index']);
    Route::post('/', [StatutRelanceController::class, 'store']);
    Route::get('{id}', [StatutRelanceController::class, 'show']);
    Route::put('{id}', [StatutRelanceController::class, 'update']);
    Route::patch('{id}', [StatutRelanceController::class, 'update']);
    Route::delete('{id}', [StatutRelanceController::class, 'destroy']);
});

Route::prefix('statut-relance-details')->group(function () {
    Route::get('/', [StatutRelanceDetailController::class, 'index']);
    Route::post('/', [StatutRelanceDetailController::class, 'store']);
    Route::get('{id}', [StatutRelanceDetailController::class, 'show']);
    Route::put('{id}', [StatutRelanceDetailController::class, 'update']);
    Route::patch('{id}', [StatutRelanceDetailController::class, 'update']);
    Route::delete('{id}', [StatutRelanceDetailController::class, 'destroy']);
});

Route::prefix('event-relances')->group(function () {
    Route::get('/', [EventRelanceController::class, 'index']);
    Route::post('/', [EventRelanceController::class, 'store']);
    Route::get('{id}', [EventRelanceController::class, 'show']);
    Route::put('{id}', [EventRelanceController::class, 'update']);
    Route::patch('{id}', [EventRelanceController::class, 'update']);
    Route::delete('{id}', [EventRelanceController::class, 'destroy']);
});

Route::prefix('sous-modeles')->group(function () {
    Route::get('/', [SousModeleController::class, 'index']);
    Route::post('/', [SousModeleController::class, 'store']);
    
    // Route::post('/generate', [SousModeleController::class, 'generateFromReleve']);
    // Route::get('/{code}/download', [SousModeleController::class, 'downloadPdf']);

    Route::get('/{code}', [SousModeleController::class, 'show']);
    Route::put('/{code}', [SousModeleController::class, 'update']);
    Route::delete('/{code}', [SousModeleController::class, 'destroy']);
});

Route::get('/clients/{code_client}/releves', [ClientController::class, 'getReleves']);
//Route::get('/clients/{code_client}/relances', [ClientController::class, 'getEtapeRelances']);
Route::get('/clients/{code_client}/relances', [RelanceDossierController::class, 'getRelancesByClient']);
Route::get('/clients/{code_client}/etape-relances', [EtapeRelanceController::class, 'getByClient']);

