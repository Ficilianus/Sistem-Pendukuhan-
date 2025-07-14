<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

// Route login (terbuka)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Semua route berikut hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    });

    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');

    Route::get('/data', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::get('/data-keluarga', [DokumenController::class, 'keluarga'])->name('dokumen.keluarga');

    Route::post('/dokumen/store', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::put('/dokumen/{id}', [DokumenController::class, 'update'])->name('dokumen.update');
    Route::delete('/dokumen/{id}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');

    Route::get('/dokumen/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::get('/dokumen/download-rt', [DokumenController::class, 'downloadByRT'])->name('dokumen.downloadByRT');
});
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
