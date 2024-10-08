<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/birds', [App\Http\Controllers\BirdController::class, 'allBirds']);
Route::post('/birds', [App\Http\Controllers\BirdController::class, 'addBird']);
Route::get('/birders', [App\Http\Controllers\BirderController::class, 'allBirders']);
Route::post('/birders', [App\Http\Controllers\BirderController::class, 'addBirder']);
