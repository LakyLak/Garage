<?php

use App\Http\Controllers\GarageController;
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

Route::get('/space', [GarageController::class, 'space']);
Route::get('/price', [GarageController::class, 'price']);
Route::post('/enter', [GarageController::class, 'enter']);
Route::post('/exit', [GarageController::class, 'exit']);


