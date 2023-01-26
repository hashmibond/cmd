<?php

use App\Http\Controllers\API\AUTH\AuthenticateController;
use App\Http\Controllers\API\TerminalActionController;
use App\Http\Controllers\API\TerminalController;
use App\Http\Controllers\API\TerminalDataReceiveController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*---------------------------terminal data receiver route-----------------------------*/
Route::any('terminals-data', [TerminalDataReceiveController::class, 'terminalDataStore']);

/*-----------------------------------auth routes-------------------------------------*/
Route::group(['prefix'=>'auth'], function(){
    Route::post('login', [AuthenticateController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::post('logout', [AuthenticateController::class, 'logout']);
        Route::post('terminals-actions', [TerminalActionController::class, 'update']);
    });
});
/*-----------------------------------user routes-------------------------------------*/
Route::group(['prefix'=>'user'], function(){
    Route::post('register', [UserController::class, 'register']);
    Route::post('create-account', [UserController::class, 'createAccount']);
    Route::post('forgot-password', [AuthenticateController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthenticateController::class, 'resetPassword']);
    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::apiResource('terminals', TerminalController::class);
        Route::post('terminal-register', [TerminalController::class,'terminalRegister']);
        Route::get('terminal-activities', [TerminalController::class, 'terminalActivities']);
        Route::get('show-profile', [UserController::class, 'userProfile']);
        Route::post('update-profile', [UserController::class, 'updateProfile']);
    });
});


