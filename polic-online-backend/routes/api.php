<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user', [UserController::class, 'user'])->middleware(['auth:sanctum']);