<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\SvcPackageController;
use Illuminate\Http\Request;
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


Route::post('/auth', [AuthController::class, 'auth']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('/customer', CustomerController::class);
    Route::resource('/service_pkg', SvcPackageController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
