<?php
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->namespace('App\Http\Controllers\Api\v1')->middleware(['isAPIValidation'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'index')->name('login');
    });
});

Route::prefix('v1')->namespace('App\Http\Controllers\Api\v1')->middleware(['auth:sanctum', 'isAPIUser', 'isAPIValidation'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
    Route::controller(BookingController::class)->group(function () {
        Route::post('booking', 'index')->name('booking');
    });
});