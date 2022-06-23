<?php

use App\Http\Controllers\TicketController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserController;
use App\Models\Timer;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'liste']);

Route::get('/tickets', [TicketController::class, 'liste']);

Route::get('/create_timer', [TimerController::class, 'index']);

Route::post('/create_timer', [TimerController::class, 'create']);