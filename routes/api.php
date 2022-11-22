<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\AuthController;

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
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::get('/{id}/verify', 'verify')->where(['id' => '[0-9]+'])->name('user.verified');
    Route::post('/login', 'createToken');

    Route::middleware('auth:sanctum')->post('/logout', 'logout'); // Я вычитал что для API не желательно делать logout, так ли это?
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('users/', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'show');
        Route::put('/{user}', 'update');
        Route::delete('/{user}', 'destroy');
    });

    Route::prefix('projects')->controller(ProjectController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'show');
        Route::post('/{project}', 'sync');
        Route::delete('/{project}', 'destroy');
    });

    Route::prefix('labels')->controller(LabelController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'show');
        Route::post('/{label}', 'sync');
        Route::delete('/{label}', 'destroy');
    });
});
