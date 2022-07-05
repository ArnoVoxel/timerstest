<?php

use App\Http\Controllers\API\TimerApiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users/force-login/{user}', [UserController::class, 'forceLogin']);
Route::get('users/logout', [UserController::class, 'logout']);
Route::get('timers/stop-timer', [TimerController::class, 'stopTimer']);


Route::apiResource('users', UserController::class);


Route::middleware('auth:api')->group( function(){

        Route::put('timers/update/{timer}', [TimerController::class, 'update']);
        Route::delete('timers/delete/{id}/{user_id}', [TimerController::class, 'destroy']);
        
        Route::apiResource('timers', TimerController::class);
        
        Route::apiResource('categories', CategoryController::class);
        
        Route::apiResource('companies', CompanyController::class);
    }
);
