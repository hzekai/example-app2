<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/tokens/create', [AuthController::class, 'create']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/getProfile', [AuthController::class, 'getProfile'])->middleware('auth:api');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::post('/tokens/create2', [MemberController::class, 'create']);
Route::post('/login2', [MemberController::class, 'login']);
Route::group([
    'middleware' => ['auth:api2'],
], function ($router) {
    Route::get('/getProfile2', [MemberController::class, 'getProfile']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
