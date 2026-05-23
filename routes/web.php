<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PhotoboxController;

Route::get('/', [PhotoboxController::class, 'index'])->name('home');
Route::get('/capture', [PhotoboxController::class, 'capture'])->name('capture');
Route::get('/frames/{uuid}', [PhotoboxController::class, 'frames'])->name('frames');
Route::get('/result/{uuid}', [PhotoboxController::class, 'result'])->name('result');
Route::get('/gallery', [PhotoboxController::class, 'gallery'])->name('gallery');
Route::post('/store-photo', [PhotoboxController::class, 'storePhoto'])->name('store.photo');
Route::post('/store-result', [PhotoboxController::class, 'storeResult'])->name('store.result');
