<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/forgot_password', [\App\Http\Controllers\AuthController::class, 'forgot_password']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/logout_everywhere', [\App\Http\Controllers\AuthController::class, 'logout_everywhere']);
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user']);
    Route::post('/reset_password', [\App\Http\Controllers\AuthController::class, 'reset_password']);
    Route::post('/change_password', [\App\Http\Controllers\AuthController::class, 'change_password']);
    Route::post('/change_email', [\App\Http\Controllers\AuthController::class, 'change_email']);

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/show', [\App\Http\Controllers\ProfileController::class, 'show']);
        Route::post('/update', [\App\Http\Controllers\ProfileController::class, 'update']);
    });
});

Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'admin'], function () {
});
