<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;

Route::get('/', function () {
    return view('home');
});

Route::get('/statistik', function () {
    return view('statistik');
});

Route::get('/data', function () {
    return view('data');
});


Route::post('/dokumen/store', [DokumenController::class, 'store'])->name('dokumen.store');
Route::get('/data', [DokumenController::class, 'index'])->name('dokumen.index');
