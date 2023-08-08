<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;

Route::get('/', [AudioController::class, 'home'])->name('home');
Route::get('/liste', [AudioController::class, 'liste'])->name('liste');
Route::post('/stream', [AudioController::class, 'stream'])->name('stream');


