<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PhotographerProfileController;
use App\Http\Controllers\PortfolioController;
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

    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{id_photo}', [PortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/portfolio/{id_photo}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::patch('/portfolio/{id_photo}', [PortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{id_photo}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

    Route::get('/availabilities', [AvailabilityController::class, 'index'])->name('availabilities.index');
    Route::get('/availabilities/create', [AvailabilityController::class, 'create'])->name('availabilities.create');
    Route::post('/availabilities', [AvailabilityController::class, 'store'])->name('availabilities.store');
    Route::get('/availabilities/{id_availability}/edit', [AvailabilityController::class, 'edit'])->name('availabilities.edit');
    Route::patch('/availabilities/{id_availability}', [AvailabilityController::class, 'update'])->name('availabilities.update');
    Route::delete('/availabilities/{id_availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id_booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{id_booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{id_booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
    Route::patch('/bookings/{id_booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{id_booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
});

require __DIR__.'/auth.php';
