<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomFacilityController;
use App\Http\Middleware\adminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{id}', [RoomController::class, 'show']);
Route::post('/bookings', [BookingController::class, 'store']);

Route::get('/available-rooms', [RoomController::class, 'available']);

Route::post('/payment-proof', [PaymentController::class, 'submitPaymentProof']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/room-facilities', [RoomFacilityController::class, 'index']);
Route::get('/rooms/{id}/booked-dates', [RoomController::class, 'bookedDates']);

Route::middleware(adminMiddleware::class)->group(function() {
    Route::get('/payments', [PaymentController::class, 'getAll']);
    Route::get('/bookings', [BookingController::class, 'getAll']);
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::post('/facilities', [RoomFacilityController::class, 'store']);
});
