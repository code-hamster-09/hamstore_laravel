<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logoutAll', [AuthController::class, 'logoutAll']);
    });
});

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')->apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);

Route::prefix('/brands')->group(function () {});
