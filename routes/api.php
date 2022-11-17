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

Route::group(['middleware' => ['missing-header']], function () {
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/forgot_password', [\App\Http\Controllers\AuthController::class, 'forgot_password']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/reset_password', [\App\Http\Controllers\AuthController::class, 'reset_password'])->middleware(['abilities:reset_token']);

        Route::group(['middleware' => ['abilities:auth_token']], function () {
            Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
            Route::post('/logout_everywhere', [\App\Http\Controllers\AuthController::class, 'logout_everywhere']);
            Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user']);
            Route::post('/change_password', [\App\Http\Controllers\AuthController::class, 'change_password']);
            Route::post('/change_email', [\App\Http\Controllers\AuthController::class, 'change_email']);
        });

        Route::group(['prefix' => 'profile', 'middleware' => ['abilities:auth_token']], function () {
            Route::get('/show', [\App\Http\Controllers\ProfileController::class, 'show']);
            Route::post('/update', [\App\Http\Controllers\ProfileController::class, 'update']);
        });

        Route::group(['prefix' => 'agent_reviews', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/create', [\App\Http\Controllers\AgentReviewController::class, 'create']);
            Route::get('/', [\App\Http\Controllers\AgentReviewController::class, 'index']);
            Route::get('/me', [\App\Http\Controllers\AgentReviewController::class, 'me']);
        });

        Route::group(['prefix' => 'users', 'middleware' => ['abilities:auth_token']], function () {
            Route::get('/agents', [\App\Http\Controllers\UserController::class, 'agents']);
            Route::get('/users', [\App\Http\Controllers\UserController::class, 'users']);
        });

        Route::group(['prefix' => 'offers', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/create', [\App\Http\Controllers\OfferController::class, 'create']);
            Route::get('/', [\App\Http\Controllers\OfferController::class, 'index']);
        });

        Route::group(['prefix' => 'coordinates', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/update', [\App\Http\Controllers\CoordinateController::class, 'update']);
            Route::get('/nearby_agents', [\App\Http\Controllers\CoordinateController::class, 'nearby_agents']);
        });
    });

    Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum', 'abilities:auth_token_admin'], 'prefix' => 'admin'], function () {
        Route::get('/offers', [\App\Http\Controllers\OfferController::class, 'index']);
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'users']);
        Route::get('/agents', [\App\Http\Controllers\UserController::class, 'agents']);
        Route::get('/agent_reviews', [\App\Http\Controllers\AgentReviewController::class, 'index']);
    });
});
