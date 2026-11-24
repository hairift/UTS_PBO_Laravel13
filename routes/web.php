<?php

use App\Http\Controllers\KatalogMenuController;
use Illuminate\Support\Facades\Route;

// Halaman pembuka hanya mengalihkan pengguna ke katalog.
Route::get('/', function () {
    return redirect()->route('menu.index');
})->name('beranda');

// URI statis ditulis dulu supaya tidak tertangkap parameter {kode}.
Route::get('/menu', [KatalogMenuController::class, 'index'])
    ->name('menu.index');

Route::get('/menu/create', [KatalogMenuController::class, 'create'])
    ->name('menu.create');

Route::post('/menu', [KatalogMenuController::class, 'store'])
    ->name('menu.store');

Route::get('/menu/{kode}', [KatalogMenuController::class, 'show'])
    ->where('kode', '[A-Za-z0-9\-]+')
    ->name('menu.show');

Route::get('/menu/{kode}/edit', [KatalogMenuController::class, 'edit'])
    ->where('kode', '[A-Za-z0-9\-]+')
    ->name('menu.edit');

// PUT dan PATCH diarahkan ke satu method, nama route tetap tunggal.
Route::match(['put', 'patch'], '/menu/{kode}', [KatalogMenuController::class, 'update'])
    ->where('kode', '[A-Za-z0-9\-]+')
    ->name('menu.update');

Route::delete('/menu/{kode}', [KatalogMenuController::class, 'destroy'])
    ->where('kode', '[A-Za-z0-9\-]+')
    ->name('menu.destroy');
