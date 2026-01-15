<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AdminAgendaController;
use App\Http\Controllers\StrippenkaartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/review', function () {
    return view('review');
})->middleware(['auth', 'verified'])->name('review');

Route::get('/overOns', function () {
    return view('overOns');
})->middleware(['auth', 'verified'])->name('overOns');

Route::get('/contact', function () {
    return view('contact');
})->middleware(['auth', 'verified'])->name('contact');

Route::get('/agenda', function () {
    return view('agenda');
})->middleware(['auth', 'verified'])->name('agenda');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/strippenkaart/add', [StrippenkaartController::class, 'add'])->name('strippenkaart.add');


require __DIR__.'/auth.php';
