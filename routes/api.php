<?php

use App\Http\Controllers\BirdController;
use App\Http\Controllers\BirderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/birds', [BirdController::class, 'allBirds']);
Route::get('/recent', [BirdController::class, 'getRecent']);
Route::get('/map', [BirdController::class, 'mapBirds']);
Route::post('/birds', [BirdController::class, 'addBird']);
Route::get('/birders', [BirderController::class, 'allBirders']);
Route::get('/all', [BirderController::class, 'birdersWithBirds']);
Route::get('/user{id}', [BirderController::class, 'getBirderBirdList']);
Route::post('/birders', [BirderController::class, 'addBirder']);
