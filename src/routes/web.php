<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
});

// 過去の試合結果を表示するページ
Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
Route::get('/matches/search', [MatchController::class, 'search'])->name('matches.search');
Route::post('/matches/search', [MatchController::class, 'search'])->name('matches.search');
Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');

// 新しい試合を保存するルート
Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
Route::get('/second', [MatchController::class, 'secondView']);
Route::get('/third', [MatchController::class, 'thirdView']);

Route::post('/players', [PlayerController::class, 'store']);