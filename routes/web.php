<?php

use App\Http\Controllers\ClipController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClipController::class, 'index'])->name('clipper.index');
Route::post('/clip', [ClipController::class, 'store'])->name('clipper.store');
Route::get('/clip/{clipJob}/status', [ClipController::class, 'status'])->name('clipper.status');
Route::get('/clip/{clipJob}/download', [ClipController::class, 'download'])->name('clipper.download');
