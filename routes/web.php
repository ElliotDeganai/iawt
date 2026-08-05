<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JourneyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/le-parcours', [JourneyController::class, 'index'])->name('journey.index');
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/galerie', [GalleryController::class, 'index'])->name('gallery.index');

// Candidature (page publique + wizard protégé)
Route::get('/candidater', [ApplicationController::class, 'index'])->name('application.index');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/candidater/formulaire', [ApplicationController::class, 'show'])->name('application.show');
    Route::patch('/candidater/formulaire', [ApplicationController::class, 'update'])->name('application.update');
    Route::get('/candidater/confirmation', [ApplicationController::class, 'submitted'])->name('application.submitted');
});

Route::get('/pays-a-l-honneur', [CountryController::class, 'current'])->name('countries.current');
Route::get('/pays-a-l-honneur/archives', [CountryController::class, 'index'])->name('countries.index');
Route::get('/pays-a-l-honneur/{country}', [CountryController::class, 'show'])->name('countries.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
