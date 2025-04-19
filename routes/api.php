<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApartmentTypeController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/types', [ApartmentTypeController::class, 'index']);

Route::middleware('auth:sanctum')->post('/apartments', [ApartmentController::class, 'store']);

Route::get('/apartments', [ApartmentController::class, 'index']);

Route::post('/filters', [FilterController::class, 'store']);

Route::post('/locations', [LocationController::class, 'store']);

Route::post('/photos', [PhotoController::class, 'store']);

Route::post('/apartments/search', [ApartmentController::class, 'search']);

Route::get('/apartments/{id}', [ApartmentController::class, 'show']);

Route::get('/apartments/{id}/orders', [OrderController::class, 'index']);

Route::post('/apartments/{id}/orders', [OrderController::class, 'store']);
