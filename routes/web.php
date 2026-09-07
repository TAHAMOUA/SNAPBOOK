<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PhotographerProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);

    Route::get('/photographer-profile', [PhotographerProfileController::class, 'create'])->name('photographer-profile.create');
    Route::post('/photographer-profile', [PhotographerProfileController::class, 'store'])->name('photographer-profile.store');
    Route::get('/photographer-profile/{id_profile}', [PhotographerProfileController::class, 'show'])->name('photographer-profile.show');
    Route::get('/photographer-profile/{id_profile}/edit', [PhotographerProfileController::class, 'edit'])->name('photographer-profile.edit');
    Route::patch('/photographer-profile/{id_profile}', [PhotographerProfileController::class, 'update'])->name('photographer-profile.update');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{id_service}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/services/{id_service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::patch('/services/{id_service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id_service}', [ServiceController::class, 'destroy'])->name('services.destroy');
});

require __DIR__.'/auth.php';