<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FishlogController;

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

Route::post('/register', [FishlogController::class, 'register']);
Route::post('/login', [FishlogController::class, 'login']);
Route::get('/list_users', [FishlogController::class, 'getUser']);
Route::get('/search_user', [FishlogController::class, 'searchUser']);
