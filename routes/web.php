<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/ministries', [FrontendController::class, 'ministries'])->name('ministries');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');

// Admin auth (guest)
Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/check-email', [RegisterController::class, 'checkEmail']);
    Route::post('/send-verification', [RegisterController::class, 'sendVerification']);
    Route::post('/verify-code', [RegisterController::class, 'verifyCode']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/validate-church-code', [RegisterController::class, 'validateChurchCode']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendTempPassword']);
    Route::get('/session-check', [LoginController::class, 'checkSession']);
});

// Admin panel (authenticated)
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::post('/logout', [LogoutController::class, 'logout']);
});
