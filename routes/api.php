<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HealthcareProfessionalController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(HealthcareProfessionalController::class)->group(function () {
    Route::get('healthcare-professionals/', 'index');
})->middleware('auth:sanctum');

Route::controller(AppointmentController::class)->group(function () {
    Route::post('appointments/', 'store');
    Route::get('appointments/', 'index');
    Route::patch('appointments/{appointment}/cancel', 'cancel');
    Route::patch('appointments/{appointment}/complete', 'complete');
})->middleware('auth:sanctum');
