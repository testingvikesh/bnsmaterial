<?php

use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PromptController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/index.php', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:6,1');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('password.change.update');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('sessions', SessionController::class);
        Route::resource('prompts', PromptController::class);
        Route::get('members', [MemberController::class, 'index'])->name('members.index');
        Route::get('members/{user}', [MemberController::class, 'show'])->name('members.show');
        Route::get('material', [MaterialController::class, 'index'])->name('material.index');
        Route::get('material/dashboard', [MaterialController::class, 'dashboard'])->name('material.dashboard');
        Route::post('material/generate', [MaterialController::class, 'generate'])->name('material.generate');
        Route::get('material/files/{materialFile}', [MaterialController::class, 'show'])->name('material.show');
        Route::get('material/files/{materialFile}/download', [MaterialController::class, 'download'])->name('material.download');

        Route::middleware('role:admin')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });
    });
});
