<?php

use App\Http\Controllers\BirdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/birds', [BirdController::class, 'allBirds']);
Route::get('/recent', [BirdController::class, 'getRecent']);
Route::get('/birds/{id}', [BirdController::class, 'getSingleBird']);
Route::get('/map', [BirdController::class, 'mapBirds']);
Route::post('/birds', [BirdController::class, 'addBird']);
Route::put('/birds/{id}', [BirdController::class, 'editBird']);
Route::delete('/birds/{id}', [BirdController::class, 'deleteBird']);

//Route::get('/birders', [BirderController::class, 'allBirders']);
//Route::get('/all', [BirderController::class, 'birdersWithBirds']);
//Route::get('/birders/{id}', [BirderController::class, 'getBirderBirdList']);
//Route::post('/birders', [BirderController::class, 'addBirder']);

Route::get('/users', [UserController::class, 'allUsers']);
Route::get('/all', [UserController::class, 'usersWithBirds']);
Route::get('/users/{id}', [UserController::class, 'getUserBirdList']);
Route::post('/users', [UserController::class, 'addUser']);
Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
