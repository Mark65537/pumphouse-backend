<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// API for residents
Route::apiResource('residents', ResidentController::class);

// API for bills
Route::apiResource('bills', BillController::class);

// API for tarifs
Route::apiResource('tarifs', TarifController::class);

// API for periods
Route::apiResource('periods', PeriodController::class);
// Or if you want to define only the update route specifically:
// Route::put('residents/{resident}', [ResidentController::class, 'update']);

