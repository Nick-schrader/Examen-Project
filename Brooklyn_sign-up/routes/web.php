<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoosterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AdminAgendaController;
use App\Http\Controllers\StrippenkaartController;
use App\Http\Controllers\ReviewController;

Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

Route::get('/review', [ReviewController::class, 'showReviews'])
    ->middleware(['auth', 'verified'])
    ->name('review');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/overOns', function () {
    return view('overOns');
})->middleware(['auth', 'verified'])->name('overOns');

Route::get('/contact', function () {
    return view('contact');
})->middleware(['auth', 'verified'])->name('contact');

Route::get('/agenda', function () {
    return view('agenda');
})->middleware(['auth', 'verified'])->name('agenda');

Route::get('/Beheer', function () {
    return view('Beheer');
})->middleware(['auth', 'verified'])->name('Beheer');

Route::middleware('auth')->group(function () {
    Route::get('/profiel', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profiel/adres', [ProfileController::class, 'adresupdate'])->name('profile.adresupdate');
    Route::delete('/profiel', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/Beheer', [ReviewController::class, 'beheer'])->name('Beheer');
    Route::post('/admin/reviews/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/admin/reviews/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
});



// leerling rooster views

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rooster', [RoosterController::class, 'get'])->name('rooster.get');
    Route::get('/rooster/history', [RoosterController::class, 'history'])->name('rooster.history');
});

Route::post('/strippenkaart/add', [StrippenkaartController::class, 'add'])->name('strippenkaart.add');


require __DIR__.'/auth.php';
