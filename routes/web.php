<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SettingsController;
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
    Route::get('/restrictions', function () {
        return view('admin.restrictions');
    })->name('admin.restrictions');
    Route::get('/questions', function () {
        return redirect()->route('admin.questions.skill');
    })->name('admin.questions');
    Route::get('/questions/skill', [QuestionController::class, 'skill'])->name('admin.questions.skill');
    Route::post('/questions/skill/update', [QuestionController::class, 'updateSkill'])->name('admin.questions.skill.update');
    Route::post('/questions/skill/reset', [QuestionController::class, 'resetSkill'])->name('admin.questions.skill.reset');
    Route::get('/questions/interest', [QuestionController::class, 'interest'])->name('admin.questions.interest');
    Route::post('/questions/interest/update', [QuestionController::class, 'updateInterest'])->name('admin.questions.interest.update');
    Route::post('/questions/interest/reset', [QuestionController::class, 'resetInterest'])->name('admin.questions.interest.reset');
    Route::get('/questions/behavioral', [QuestionController::class, 'behavioral'])->name('admin.questions.behavioral');
    Route::post('/questions/behavioral/update', [QuestionController::class, 'updateBehavioral'])->name('admin.questions.behavioral.update');
    Route::post('/questions/behavioral/reset', [QuestionController::class, 'resetBehavioral'])->name('admin.questions.behavioral.reset');
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/church-name', [SettingsController::class, 'updateChurchName'])->name('admin.settings.church-name');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('admin.settings.password');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('admin.logout');
});
