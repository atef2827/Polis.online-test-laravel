<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::post('/signup', [UserController::class, 'signup']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});