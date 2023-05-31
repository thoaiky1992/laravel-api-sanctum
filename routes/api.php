<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        // users
        Route::get('/users/scope', [UserController::class, 'scope']);
        Route::apiResource('users', 'App\Http\Controllers\UserController');

        // posts
        Route::get('/posts/search', [UserController::class, 'search']);
    });
});
