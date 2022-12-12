<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\SportController as SportAdminController;
use App\Http\Controllers\Admin\LeagueController as LeagueAdminController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\PayloadController;


use App\Http\Controllers\Bets\BetPurchaseController;
use App\Http\Controllers\Bets\LeagueController;
use App\Http\Controllers\Bets\MatcheController;

use App\Http\Controllers\Sports\LeagueController as SportsLeagueController;
use App\Http\Controllers\Sports\CountryController as SportsCountryController;
use App\Http\Controllers\Sports\MatcheController as SportsMatcheController;
use App\Http\Controllers\Sports\OddPrematcheController as SportsOddPrematcheController;
use App\Http\Controllers\Sports\ResultController as SportsResultController;


Route::group(['prefix' => 'auth'], function(){
	Route::post('/register', [AuthController::class, 'register']);
	Route::post('/login', [AuthController::class, 'login']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'auth'], function(){
    	Route::post('/logout', [AuthController::class, 'logout']);
    	Route::put('/update/{id}', [AuthController::class, 'update']);
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

	Route::group(['prefix' => 'country'], function(){
		Route::get('/', [CountryController::class, 'index']);
		Route::post('/', [CountryController::class, 'store']);
		Route::get('/all', [CountryController::class, 'all']);
		Route::get('/search/{column}/{value}', [CountryController::class, 'search']);
		Route::put('/{id}', [CountryController::class, 'update']);
		Route::get('/{id}', [CountryController::class, 'show']);
		Route::delete('/{id}', [CountryController::class, 'destroy']);
	});

	Route::group(['prefix' => 'league'], function(){
		Route::get('/', [LeagueAdminController::class, 'index']);
		Route::get('/all', [LeagueAdminController::class, 'all']);
		Route::get('/by-country/{countryId}', [LeagueAdminController::class, 'byCountry']);
		Route::get('/search/{column}/{value}', [LeagueAdminController::class, 'search']);
		Route::put('/{id}', [LeagueAdminController::class, 'update']);
		Route::post('/update-active-leagues', [LeagueAdminController::class, 'updateActiveLeagues']);
		Route::get('/{id}', [LeagueAdminController::class, 'show']);
	});

	Route::group(['prefix' => 'sport'], function(){
		Route::get('/', [SportAdminController::class, 'index']);
	});

	Route::group(['prefix' => 'bet-purchase'], function(){
		Route::get('/', [BetPurchaseController::class, 'index']);
		Route::post('/', [BetPurchaseController::class, 'store']);
		Route::get('/all', [BetPurchaseController::class, 'all']);
		Route::get('/search/{column}/{value}', [BetPurchaseController::class, 'search']);
		Route::put('/{id}', [BetPurchaseController::class, 'update']);
		Route::get('/{id}', [BetPurchaseController::class, 'show']);
		Route::delete('/{id}', [BetPurchaseController::class, 'destroy']);
	});

	Route::group(['prefix' => 'deposit'], function(){
		Route::get('/', [DepositController::class, 'index']);
		Route::post('/', [DepositController::class, 'store']);
		Route::get('/all', [DepositController::class, 'all']);
		Route::get('/search/{column}/{value}', [DepositController::class, 'search']);
		Route::put('/{id}', [DepositController::class, 'update']);
		Route::get('/{id}', [DepositController::class, 'show']);
		Route::delete('/{id}', [DepositController::class, 'destroy']);
	});

	Route::group(['prefix' => 'payload'], function(){
		Route::get('/create-payment/{value}', [PayloadController::class, 'createPayment']);
	});

	Route::group(['prefix' => 'sports'], function(){
		Route::get('/leagues-by-country/{codeCountry}/{sportName}', [SportsLeagueController::class, 'getLeaguesByCountryOfAPIOut']);
		Route::post('/register-matches', [SportsMatcheController::class, 'registerMatches']);
		Route::post('/register-odds', [SportsOddPrematcheController::class, 'registerOdds']);
		
		Route::post('/register-results-soccer', [SportsResultController::class, 'registerResultsSoccer']);

	});
});

Route::group(['prefix' => 'bets'], function(){
	Route::group(['prefix' => 'leagues'], function(){
		Route::get('/all-country-leagues', [LeagueController::class, 'allCountryLeagues']);
		Route::get('/all-league-matches/{apieventsSportId}/{apieventsLeagueId}', [LeagueController::class, 'allLeagueMatches']);
	});

	Route::group(['prefix' => 'matches'], function(){
		Route::get('/matche-odds/{apieventsSportId}/{apieventsLeagueId}/{bet365MatcheId}', [MatcheController::class, 'matcheOdds']);
	});
});

Route::group(['prefix' => 'payload'], function(){
	Route::post('/verify-payment', [PayloadController::class, 'verifyPayment']);
});



