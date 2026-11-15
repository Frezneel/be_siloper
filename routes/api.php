<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleTypeContoller;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\EnsureChangePassword;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'updatePassword']);
    Route::group(['middleware' => EnsureChangePassword::class], function () {
        // Add routes that require password change here
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);

        // Users
        Route::group(['middleware' => 'ability:SYSTEM:MANAGE_USERS'], function(){
            Route::post('/users', [UserController::class, 'create']);
            Route::match(['put', 'patch'],'/users/{user}', [UserController::class,'update']);
            Route::get('/users/{user}', [UserController::class,'getById']);
            Route::get('/users', [UserController::class,'index']);
            Route::post('/users/{user}/reset-password', [UserController::class,'resetPassword']);
            Route::delete('/users/{user}', [UserController::class,'destroy']);
        });

        // Role Types
        Route::group(['middleware'=> 'ability:SYSTEM:VIEW_ROLES'], function(){
            Route::get('/role-types', [RoleTypeContoller::class,'getAll']);
            Route::get('/role-types/{id}', [RoleTypeContoller::class,'findId']);
            Route::post('/role-types', [RoleTypeContoller::class, 'store']);
            Route::delete('/role-types/{id}', [RoleTypeContoller::class,'destroy']);
        });
    });
});
