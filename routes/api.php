<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

Route::group(['prefix' => 'auth'], function(){
	Route::post('/register', [AuthController::class, 'register']);
	Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'auth'], function(){
    	Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::group(['prefix' => 'dashboard'], function(){
		Route::get('/all-data', [DashboardController::class, 'index']);
	});

    Route::group(['prefix' => 'role'], function(){
		Route::get('/', [RoleController::class, 'index']);
		Route::get('/all', [RoleController::class, 'all']);
		Route::get('/search/{column}/{value}', [RoleController::class, 'search']);
		Route::post('/', [RoleController::class, 'store']);
		Route::put('/{id}', [RoleController::class, 'update']);
		Route::get('/{id}', [RoleController::class, 'show']);
		Route::delete('/{id}', [RoleController::class, 'destroy']);
		Route::delete('/remove-permission/{idRole}/{idPermission}', [RoleController::class, 'removePermission']);
	});

	Route::group(['prefix' => 'permission'], function(){
		Route::get('/', [PermissionController::class, 'index']);
		Route::get('/all', [PermissionController::class, 'all']);
		Route::get('/search/{column}/{value}', [PermissionController::class, 'search']);
		Route::get('/{id}', [PermissionController::class, 'show']);
	});

    Route::group(['prefix' => 'user'], function(){
		Route::get('/', [UserController::class, 'index']);
		Route::post('/', [UserController::class, 'store']);
		Route::get('/all', [UserController::class, 'all']);
		Route::get('/search/{column}/{value}', [UserController::class, 'search']);
		Route::put('/{id}', [UserController::class, 'update']);
		Route::get('/{id}', [UserController::class, 'show']);
		Route::delete('/{id}', [UserController::class, 'destroy']);
		Route::delete('/remove-role/{idUser}/{idRole}', [UserController::class, 'removeRole']);
		Route::delete('/remove-permission/{idUser}/{idPermission}', [UserController::class, 'removePermission']);
	});

});


