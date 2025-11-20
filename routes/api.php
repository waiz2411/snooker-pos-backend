<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\GameController;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::delete('/clubs/{id}', [ClubController::class, 'deleteClub']);
Route::put('/clubs/{id}', [ClubController::class, 'updateClub']);
Route::get('/clubs', [ClubController::class, 'getAllClubs']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/clubs/{id}', [ClubController::class, 'getClubById']);
});


// TABLE MANAGEMENT
Route::post('/clubs/{club_id}/tables', [TableController::class, 'createTable']);
Route::get('/clubs/{club_id}/tables', [TableController::class, 'getTables']);
Route::get('/tables/{table_id}', [TableController::class, 'getTable']);
Route::put('/tables/{table_id}', [TableController::class, 'updateTable']);
Route::delete('/tables/{table_id}', [TableController::class, 'deleteTable']);


Route::prefix('/clubs/{club_id}')->group(function () {
    Route::post('/customers', [CustomerController::class, 'createCustomer']);
    Route::get('/customers', [CustomerController::class, 'getCustomers']);
    Route::get('/customers/{customer_id}', [CustomerController::class, 'getCustomer']);
    Route::put('/customers/{customer_id}', [CustomerController::class, 'updateCustomer']);
    Route::delete('/customers/{customer_id}', [CustomerController::class, 'deleteCustomer']);
});

// Route::middleware('auth:sanctum')->group(function () {
// });

// GAME MANAGEMENT
Route::post('/clubs/{club_id}/games', [GameController::class, 'createGame']);
Route::put('/games/{game_id}/complete', [GameController::class, 'completeGame']);
Route::get('/clubs/{club_id}/games', [GameController::class, 'getGames']);
Route::get('/games/{game_id}', [GameController::class, 'getGameById']);
Route::put('/games/{game_id}', [GameController::class, 'updateGame']);
Route::delete('/games/{game_id}', [GameController::class, 'deleteGame']);
