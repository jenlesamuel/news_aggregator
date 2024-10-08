<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth.jwt')->group(function() {
        Route::get('/newsfeed', [ArticlesController::class, 'getUserNewsfeed']);
        Route::get('/articles/search', [ArticlesController::class, 'search']);
        Route::get('/preference', [PreferenceController::class, 'getPreference']);
        Route::post('/preference', [PreferenceController::class, 'updatePreference']);
        Route::get('/preference/options', [PreferenceController::class, 'getPreferenceOptions']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});


