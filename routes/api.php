<?php

use App\Http\Controllers\BirdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/birds', [BirdController::class, 'allBirds']);
Route::get('/recent', [BirdController::class, 'getRecent']);
Route::post('/birds', [BirdController::class, 'addBird']);
Route::get('/birders', [App\Http\Controllers\BirderController::class, 'allBirders']);
Route::post('/birders', [App\Http\Controllers\BirderController::class, 'addBirder']);
