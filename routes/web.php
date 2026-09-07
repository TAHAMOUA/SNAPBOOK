<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PhotographerProfileController;
use App\Http\Controllers\ProfileController;
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
});

require __DIR__.'/auth.php';