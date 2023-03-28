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

        Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->middleware(['abilities:auth_token']);
        Route::post('/create_interests', [\App\Http\Controllers\CategoryController::class, 'create_interests'])->middleware(['abilities:auth_token']);
        Route::get('/my_interests', [\App\Http\Controllers\CategoryController::class, 'my_interests'])->middleware(['abilities:auth_token']);
        Route::post('/add_balance', [\App\Http\Controllers\BalanceController::class, 'add_balance'])->middleware(['abilities:auth_token']);

        Route::group(['middleware' => ['abilities:auth_token']], function () {
            Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
            Route::post('/logout_everywhere', [\App\Http\Controllers\AuthController::class, 'logout_everywhere']);
            Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user']);
            Route::post('/change_password', [\App\Http\Controllers\AuthController::class, 'change_password']);
            Route::post('/change_email', [\App\Http\Controllers\AuthController::class, 'change_email']);
        });

        Route::group(['prefix' => 'misc', 'middleware' => ['abilities:auth_token']], function () {
            // Route::post('/create', [\App\Http\Controllers\MiscController::class, 'create']);
            Route::post('/upsert', [\App\Http\Controllers\MiscController::class, 'upsert']);
        });

        Route::group(['prefix' => 'profile', 'middleware' => ['abilities:auth_token']], function () {
            Route::get('/show', [\App\Http\Controllers\ProfileController::class, 'show']);
            Route::post('/update', [\App\Http\Controllers\ProfileController::class, 'update']);
        });

        Route::group(['prefix' => 'messages', 'middleware' => ['abilities:auth_token']], function () {
            Route::get('/', [\App\Http\Controllers\MessageController::class, 'index']);
            Route::get('/index_users', [\App\Http\Controllers\MessageController::class, 'index_users']);
            Route::post('/create', [\App\Http\Controllers\MessageController::class, 'store']);
        });

        Route::group(['prefix' => 'agent_reviews', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/create', [\App\Http\Controllers\AgentReviewController::class, 'create']);
            Route::get('/', [\App\Http\Controllers\AgentReviewController::class, 'index']);
            Route::get('/me', [\App\Http\Controllers\AgentReviewController::class, 'me']);
        });

        Route::group(['prefix' => 'users', 'middleware' => ['abilities:auth_token']], function () {
            Route::get('/agents', [\App\Http\Controllers\UserController::class, 'agents']);
            Route::get('/featured_agents', [\App\Http\Controllers\UserController::class, 'featured_agents']);
            Route::get('/search_agent', [\App\Http\Controllers\UserController::class, 'search_agent']);
            Route::get('/users', [\App\Http\Controllers\UserController::class, 'users']);
        });

        Route::group(['prefix' => 'user_details', 'middleware' => ['abilities:auth_token']], function () {
            Route::get('/terms_and_conditions', [\App\Http\Controllers\UserDetailController::class, 'get_terms_and_conditions']);
            Route::post('/terms_and_conditions', [\App\Http\Controllers\UserDetailController::class, 'post_terms_and_conditions']);
            Route::get('/privacy_policy', [\App\Http\Controllers\UserDetailController::class, 'get_privacy_policy']);
            Route::post('/privacy_policy', [\App\Http\Controllers\UserDetailController::class, 'post_privacy_policy']);
            Route::get('/onboarding', [\App\Http\Controllers\UserDetailController::class, 'get_onboarding']);
            Route::post('/onboarding', [\App\Http\Controllers\UserDetailController::class, 'post_onboarding']);
            Route::post('/disable_push_notifications', [\App\Http\Controllers\UserDetailController::class, 'disable_push_notifications']);
            Route::post('/enable_push_notifications', [\App\Http\Controllers\UserDetailController::class, 'enable_push_notifications']);
            Route::get('/', [\App\Http\Controllers\UserDetailController::class, 'show']);
        });

        Route::group(['prefix' => 'support', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/contact_us', [\App\Http\Controllers\SupportController::class, 'contact_us']);
            Route::get('/support_tickets', [\App\Http\Controllers\SupportController::class, 'support_tickets']);
            Route::post('/support_ticket/{id}', [\App\Http\Controllers\SupportController::class, 'resolve_support_ticket']);
        });

        Route::group(['prefix' => 'offers', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/create', [\App\Http\Controllers\OfferController::class, 'create']);
            Route::get('/', [\App\Http\Controllers\OfferController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\OfferController::class, 'show']);
        });

        Route::group(['prefix' => 'coordinates', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/update', [\App\Http\Controllers\CoordinateController::class, 'update']);
            Route::get('/nearby_agents', [\App\Http\Controllers\CoordinateController::class, 'nearby_agents']);
        });

        Route::group(['prefix' => 'projects', 'middleware' => ['abilities:auth_token']], function () {
            Route::post('/create', [\App\Http\Controllers\ProjectController::class, 'create']);
            Route::get('/', [\App\Http\Controllers\ProjectController::class, 'projects']);
            Route::get('/{id}', [\App\Http\Controllers\ProjectController::class, 'project']);
            Route::post('/complete/{id}', [\App\Http\Controllers\ProjectController::class, 'mark_completed']);
        });
    });

    Route::post('/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum', 'abilities:auth_token_admin'], 'prefix' => 'admin'], function () {
        Route::get('/offers', [\App\Http\Controllers\OfferController::class, 'index']);
        Route::get('/offers/{id}', [\App\Http\Controllers\OfferController::class, 'show']);
        Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'index']);
        Route::get('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'show']);
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'users']);
        Route::get('/agents', [\App\Http\Controllers\UserController::class, 'agents']);
        Route::get('/agent_reviews', [\App\Http\Controllers\AgentReviewController::class, 'index']);
        Route::get('/user_chart', [\App\Http\Controllers\UserController::class, 'users_created_count_by_month']);
        Route::get('/agent_chart', [\App\Http\Controllers\UserController::class, 'agents_created_count_by_month']);

        Route::group(['prefix' => 'user'], function () {
            Route::get('/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
            Route::post('/update_profile/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update_profile']);
            Route::post('/update_password/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update_password']);
        });

        // Route::group(['prefix' => 'user_role'], function () {
        //     Route::get('/', [\App\Http\Controllers\Admin\UserRoleController::class, 'index']);
        //     Route::get('/search', [\App\Http\Controllers\Admin\UserRoleController::class, 'search']);
        //     Route::get('/filter', [\App\Http\Controllers\Admin\UserRoleController::class, 'filter']);
        // });

        Route::group(['prefix' => 'agents'], function () {
            Route::get('/unapproved_agents', [\App\Http\Controllers\Admin\AgentController::class, 'unapproved_agents']);
            Route::post('/approve_agent', [\App\Http\Controllers\Admin\AgentController::class, 'approve_agent']);
        });

        Route::group(['prefix' => 'support'], function () {
            Route::get('/support_tickets', [\App\Http\Controllers\Admin\SupportController::class, 'support_tickets']);
            Route::get('/support_ticket/{id}', [\App\Http\Controllers\Admin\SupportController::class, 'support_ticket']);
            Route::post('/support_ticket/{id}', [\App\Http\Controllers\Admin\SupportController::class, 'resolve_support_ticket']);
            Route::post('/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply']);
        });

        Route::group(['prefix' => 'documents'], function () {
            Route::post('/terms_and_conditions', [\App\Http\Controllers\Admin\DocumentController::class, 'update_terms_and_conditions']);
            Route::post('/privacy_policy', [\App\Http\Controllers\Admin\DocumentController::class, 'update_privacy_policy']);
            Route::get('/terms_and_conditions', [\App\Http\Controllers\Admin\DocumentController::class, 'get_terms_and_conditions']);
            Route::get('/privacy_policy', [\App\Http\Controllers\Admin\DocumentController::class, 'get_privacy_policy']);
        });
    });
});
