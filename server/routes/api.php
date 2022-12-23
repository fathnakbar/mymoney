<?php

use Illuminate\Http\Request;
use App\Http\Controllers\api\AuthenticatedSessionAPIController;
use App\Http\Controllers\api\RegisteredUserAPIController;
use App\Http\Controllers\api\WalletController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisteredUserAPIController::class, 'store']);
Route::post('login', [AuthenticatedSessionAPIController::class, 'store']);

Route::post('check_token', [AuthenticatedSessionAPIController::class, 'check_token']);

Route::resource('wallet', WalletController::class);
