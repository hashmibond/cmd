<?php

use App\Http\Controllers\TerminalActionController;
use App\Http\Controllers\AUTH\AuthenticateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthenticateController::class,'loginPage'])->name('LoginPage');
Route::post('web-login', [AuthenticateController::class,'webLogin'])->name('WebLogin');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class,'index'])->name('Dashboard');
    Route::post('received-datatable', [DashboardController::class,'datatable'])->name('receivedDatatable');
    Route::post('web-logout', [AuthenticateController::class,'webLogout'])->name('WebLogout');
    Route::resource('terminals', TerminalActionController::class);
    //Route::get('terminals/destroy/{$id}', [TerminalActionController::class,'destroy']);
    Route::post('terminals-datatable', [TerminalActionController::class, 'datatable'])->name('terminalsDatatable');
    Route::resource('users', UserController::class);
    Route::post('users-datatable', [UserController::class, 'datatable'])->name('usersDatatable');
});




