<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AuthController;

Route::middleware("auth:sanctum")->get("/user", fn (Request $request): JsonResponse => new JsonResponse($request->user()));

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::post('register', [AuthController::class, 'register']);

    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
;


Route::prefix('appointments')->group(function () {
    Route::get("/{id}", [AppointmentController::class, "show"]);
    Route::post("/", [AppointmentController::class, "store"])->middleware("auth:sanctum");
    Route::put("/{id}", [AppointmentController::class, "update"])->middleware("auth:sanctum");
    Route::delete("/{id}", [AppointmentController::class, "destroy"])->middleware("auth:sanctum");
});


Route::prefix('clients')->group(function () {
    Route::get("/{id}", [ClientController::class, "show"]);
    Route::post("/", [ClientController::class, "store"])->middleware("auth:sanctum");
    Route::put("/{id}", [ClientController::class, "update"])->middleware("auth:sanctum");
    Route::delete("/{id}", [ClientController::class, "destroy"])->middleware("auth:sanctum");
});


Route::prefix('doctors')->group(function () {
    Route::get("/{id}", [DoctorController::class, "show"]);
    Route::post("/", [DoctorController::class, "store"])->middleware("auth:sanctum");
    Route::put("/{id}", [DoctorController::class, "update"])->middleware("auth:sanctum");
    Route::delete("/{id}", [DoctorController::class, "destroy"])->middleware("auth:sanctum");
});
