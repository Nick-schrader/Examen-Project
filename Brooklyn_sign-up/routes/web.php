<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoosterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AdminAgendaController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\StrippenkaartController;
use App\Http\Controllers\ReviewController;

Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/agenda/lesson-data', [AgendaController::class, 'getLessonData'])->name('agenda.lesson-data');
    Route::post('/agenda/add-lesson', [AgendaController::class, 'addLesson'])->name('agenda.add-lesson');
    Route::post('/agenda/assign-timeblock', [AgendaController::class, 'assignTimeBlock'])->name('agenda.assign-timeblock');
    Route::post('/agenda/delete-timeblock', [AgendaController::class, 'deleteTimeBlock'])->name('agenda.deleteTimeBlock');
    Route::get('/api/students', [AgendaController::class, 'getStudents']);
    Route::get('/api/cars', [AgendaController::class, 'getCars']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profiel', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profiel', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profiel/adres', [ProfileController::class, 'adresupdate'])->name('profile.adresupdate');
    Route::delete('/profiel', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// leerling rooster views

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rooster', [RoosterController::class, 'get'])->name('rooster.get');
    Route::get('/rooster/history', [RoosterController::class, 'history'])->name('rooster.history');
});

// Wagenpark views
Route::get('/wagenpark', [AutoController::class, 'index'])->middleware(['auth', 'verified'])->name('wagenpark');
Route::put('/autos/{id}', [AutoController::class, 'update'])->name('autos.update');
Route::post('/autos', [AutoController::class    , 'store'])->name('autos.store');
Route::delete('/autos/remove/{id}', [AutoController::class, 'remove'])->name('autos.remove');
// Get overview graph data (all cars)
Route::get('/autos/usage-data', [AutoController::class, 'getCarUsageData'])
    ->name('autos.usage-data');

// Get specific car graph data
Route::get('/autos/{id}/usage-data', [AutoController::class, 'getCarUsageData'])
    ->name('autos.usage-data.single');

// Image uploader
Route::get('/autos/images', [AutoController::class, 'getCarImages'])->name('autos.images');
Route::post('/autos/upload-image', [AutoController::class, 'uploadCarImage'])->name('autos.upload-image');

// DEBUG
Route::get('/autos/debug-usage', [AutoController::class, 'debugUsageData']);
Route::post('/strippenkaart/add', [StrippenkaartController::class, 'add'])->name('strippenkaart.add');


require __DIR__.'/auth.php';
