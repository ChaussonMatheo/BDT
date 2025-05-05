<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\GarageReservationController;
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

Route::get('/garage-reservations', [GarageReservationController::class, 'apiEvents']);
Route::get('/rendezvous', [RendezVousController::class, 'apiEvents']);
Route::get('/rendezvous/{id}', [RendezVousController::class, 'apiShow']);
Route::get('/garage-reservations/{id}', [GarageReservationController::class, 'apiShow']);

