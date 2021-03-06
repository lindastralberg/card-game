<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardGameController;

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
    return view('index');
});

Route::get('/api/new_deck', [CardGameController::class, 'getNewDeck']);
Route::get('/api/{deck_id}/draw', [CardGameController::class, 'drawCard']);
