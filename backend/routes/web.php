<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Citizen\CitizenDashboardController;
use App\Http\Controllers\Citizen\CitizenNotificationController;
use App\Http\Controllers\Citizen\CitizenProfileController;
use App\Http\Controllers\Citizen\CitizenReportController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Responder\ResponderAnalyticsController;
use App\Http\Controllers\Responder\ResponderDashboardController;
use App\Http\Controllers\Responder\ResponderEmergencyController;
use App\Http\Controllers\Responder\ResponderMapController;
use App\Http\Controllers\Responder\ResponderNotificationController;
use App\Http\Controllers\Responder\ResponderProfileController;
use App\Http\Controllers\Responder\ResponderSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/how-it-works', [PublicController::class, 'howItWorks'])->name('how-it-works');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeUser'])->name('register.post');

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot-password.post');

Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('reset-password.post');

Route::get('/responder/login', [AuthController::class, 'responderLogin'])->name('responder.login');
Route::post('/responder/login', [AuthController::class, 'authenticateResponder'])->name('responder.login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Citizen Application Routes
|--------------------------------------------------------------------------
*/
Route::prefix('')->name('citizen.')->group(function () {
    Route::get('/dashboard', [CitizenDashboardController::class, 'index'])->name('dashboard');

    Route::get('/reports', [CitizenReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [CitizenReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [CitizenReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}/submitted', [CitizenReportController::class, 'submitted'])->name('reports.submitted');
    Route::get('/reports/{id}', [CitizenReportController::class, 'show'])->name('reports.show');

    Route::get('/notifications', [CitizenNotificationController::class, 'index'])->name('notifications');

    Route::get('/profile', [CitizenProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [CitizenProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [CitizenProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Emergency Responder / Operations Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('responder')->name('responder.')->group(function () {
    Route::get('/dashboard', [ResponderDashboardController::class, 'index'])->name('dashboard');

    Route::get('/emergencies', [ResponderEmergencyController::class, 'index'])->name('emergencies.index');
    Route::get('/emergencies/{id}', [ResponderEmergencyController::class, 'show'])->name('emergencies.show');
    Route::post('/emergencies/{id}/status', [ResponderEmergencyController::class, 'updateStatus'])->name('emergencies.update-status');

    Route::get('/map', [ResponderMapController::class, 'index'])->name('map');
    Route::get('/analytics', [ResponderAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/notifications', [ResponderNotificationController::class, 'index'])->name('notifications');

    Route::get('/profile', [ResponderProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ResponderProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ResponderProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/settings', [ResponderSettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [ResponderSettingsController::class, 'update'])->name('settings.update');
});
