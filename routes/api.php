<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkplaceController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskImageController;
use App\Http\Controllers\Api\TaskRoomController;
use App\Http\Controllers\Api\AddressController;

Route::get('/verify-token', function (Request $request) {
    $user = $request->user(); 

    if ($user) {
        return response()->json(['role' => $user->role], 200);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('workplaces', WorkplaceController::class);
        Route::apiResource('rooms', RoomController::class);
        Route::apiResource('tasks', TaskController::class);

        Route::apiResource('tasks', TaskController::class);
        Route::post('task-images', [TaskImageController::class, 'store']);
        Route::delete('task-images/{id}', [TaskImageController::class, 'destroy']);
        Route::post('task-rooms', [TaskRoomController::class, 'store']);
        Route::delete('task-rooms/{id}', [TaskRoomController::class, 'destroy']);

    });

    Route::prefix('user')->group(function () {


    });

    Route::get('/addresses/{zipcode}', [AddressController::class, 'showByZipCode']);
});