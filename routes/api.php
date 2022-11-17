<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\LabelController;

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

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'show']);
    Route::get('/{id}/verify', [UserController::class, 'verify'])->name('user.verified');
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('projects')->group(function () {
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/', [ProjectController::class, 'show']);
    Route::post('/{id}', [ProjectController::class, 'sync']);
    Route::delete('/{id}', [ProjectController::class, 'destroy']);
});

Route::prefix('labels')->group(function () {
    Route::post('/', [LabelController::class, 'store']);
    Route::get('/', [LabelController::class, 'show']);
    Route::post('/{id}', [LabelController::class, 'sync']);
    Route::delete('/{id}', [LabelController::class, 'destroy']);
});
