<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/liste', [AudioController::class, 'liste'])->name('liste');
Route::post('/stream', [AudioController::class, 'stream'])->name('stream');


