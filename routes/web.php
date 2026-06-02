<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;

// ─── Public Routes ────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// ─── Breeze Auth Routes ───────────────────────────────────────
require __DIR__.'/auth.php';


// ─── Student Routes ───────────────────────────────────────────
Route::middleware(['auth', 'verified', 'student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/create', [ProfileController::class, 'create'])->name('create');
        Route::post('/create', [ProfileController::class, 'store'])->name('store');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/edit', [ProfileController::class, 'update'])->name('update');
    });

    // Applications
    Route::middleware(['profile.complete'])
    ->prefix('applications')
    ->name('applications.')
    ->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create', [ApplicationController::class, 'create'])->name('create');
        Route::post('/create', [ApplicationController::class, 'store'])->name('store');
        Route::get('/{application}', [ApplicationController::class, 'show'])->name('show');
        Route::post('/{application}/submit', [ApplicationController::class, 'submit'])->name('submit');
        Route::post('/{application}/update', [ApplicationController::class, 'update'])->name('update');
    });
});

// ─── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
        Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
        Route::post('/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('status');
    });

    // Students
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'students'])->name('index');
        Route::get('/{user}', [AdminApplicationController::class, 'showStudent'])->name('show');
    });
});