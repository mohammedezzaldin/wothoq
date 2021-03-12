<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::post('add_challange', [Api::class, 'add_challange']);

Route::get('show_challange/{id}', [Api::class, 'show_challange']);

Route::get('upcoming', [Api::class, 'upcoming']);
Route::get('feature', [Api::class, 'feature']);
Route::get('completed', [Api::class, 'completed']);

Route::post('add_clip', [Api::class, 'add_clip']);
Route::post('add_vote', [Api::class, 'add_vote']);
Route::get('winners/{id}', [Api::class, 'winners']);