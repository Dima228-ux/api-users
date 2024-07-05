<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'getUser']);
        Route::post('/', [UserController::class, 'updateUser']);
        Route::delete('/',[UserController::class, 'deleteUser']);
    });
    Route::delete('sign-out', [UserController::class, 'signOut']);
});

Route::post('sign-up', [UserController::class, 'signUp']);
Route::post('sign-in', [UserController::class, 'signIn']);
