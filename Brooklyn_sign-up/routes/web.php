<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profiel', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profiel/adres', [ProfileController::class, 'adresupdate'])->name('profile.adresupdate');
    Route::delete('/profiel', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/rooster', function () {
    return view('rooster');
})->middleware(['auth', 'verified'])->name('rooster');

// Admin views

Route::get('/admin/agenda', function () {
    return view('admin-views/agenda');
})->middleware(['auth', 'verified'])->name('agenda');


require __DIR__.'/auth.php';
