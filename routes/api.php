<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\CustomerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);



Route::middleware(['auth:api'])->group(function () {
    Route::get('medications', [MedicationController::class, 'index']);
    Route::get('medications/{id}', [MedicationController::class, 'show']);
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::put('medications/{id}', [MedicationController::class, 'update']);
    Route::put('customers/{id}', [CustomerController::class, 'update']);
});

Route::middleware(['auth:api', 'owner'])->group(function () {
    Route::post('medications', [MedicationController::class, 'store']);
    Route::delete('medications/delete-permenant/{id}', [MedicationController::class, 'permanantDelete']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::delete('customers/delete-permenant/{id}', [CustomerController::class, 'permanantDelete']);
});

Route::middleware(['auth:api', 'managerOrOwner'])->group(function () {
    Route::delete('medications/{id}', [MedicationController::class, 'destroy']);
    Route::delete('customers/{id}', [CustomerController::class, 'destroy']);
});
