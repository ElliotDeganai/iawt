<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\CountryMediaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EngagementController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FeaturedCountryController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\JourneyStepController;
use App\Http\Controllers\Admin\LegalController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PlaceCategoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class)->except('show');
        Route::resource('roles', RoleController::class)->except('show');
        Route::resource('countries', FeaturedCountryController::class)->except('show');
        Route::put('countries/{country}/set-current', [FeaturedCountryController::class, 'setCurrent'])->name('countries.set-current');
        Route::resource('place-categories', PlaceCategoryController::class)->only(['index', 'store', 'update', 'destroy']);

        // Galerie médias
        Route::post('countries/{country}/media', [CountryMediaController::class, 'store'])->name('countries.media.store');
        Route::post('countries/{country}/media/video', [CountryMediaController::class, 'storeVideo'])->name('countries.media.store-video');
        Route::delete('countries/{country}/media/{media}', [CountryMediaController::class, 'destroy'])->name('countries.media.destroy');
        Route::put('countries/{country}/media/{media}/cover', [CountryMediaController::class, 'setCover'])->name('countries.media.set-cover');
        Route::post('countries/{country}/media/reorder', [CountryMediaController::class, 'reorder'])->name('countries.media.reorder');
        Route::patch('countries/{country}/media/{media}/caption', [CountryMediaController::class, 'updateCaption'])->name('countries.media.caption');
        Route::resource('engagements', EngagementController::class)->except('show');
        Route::resource('journey-steps', JourneyStepController::class)->except('show');

        Route::get('home-content', [HomeContentController::class, 'edit'])->name('home-content.edit');
        Route::put('home-content', [HomeContentController::class, 'update'])->name('home-content.update');

        Route::resource('stats', StatController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('partners', PartnerController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('testimonials', TestimonialController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::put('testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggleApproval'])->name('testimonials.toggle');

        // Candidatures
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::put('applications/{application}/accept', [ApplicationController::class, 'accept'])->name('applications.accept');
        Route::put('applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
        Route::put('applications/{application}/unlock-step', [ApplicationController::class, 'unlockStep'])->name('applications.unlock-step');
        Route::post('applications/{application}/comment', [ApplicationController::class, 'comment'])->name('applications.comment');

        Route::resource('events', EventController::class)->except('show');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('legal', [LegalController::class, 'edit'])->name('legal.edit');
        Route::put('legal', [LegalController::class, 'update'])->name('legal.update');
    });