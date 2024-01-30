<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

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

Route::group([
    'middleware' =>
    'auth:sanctum'
], function () {
    Route::prefix('auth')->withoutMiddleware('auth:sanctum')->group(function () {
        $limiter = config('fortify.limiters.login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware(array_filter([
            'guest:' . config('fortify.guard'),
            $limiter ? 'throttle:' . $limiter : null
        ]));
        Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest:' . config('fortify.guard'));
    });
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
