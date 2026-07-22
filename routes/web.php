<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\RestrictionController;
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

// Assessment
Route::get('/assessment', function () {
    return view('assessment.index');
})->name('assessment');

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('admin.dashboard.data');
    Route::get('/restrictions', function () {
        return redirect()->route('admin.restrictions.demographics');
    })->name('admin.restrictions');
    Route::get('/restrictions/demographics', [RestrictionController::class, 'demographics'])->name('admin.restrictions.demographics');
    Route::post('/restrictions/demographics/update', [RestrictionController::class, 'updateDemographics'])->name('admin.restrictions.demographics.update');
    Route::post('/restrictions/demographics/reset', [RestrictionController::class, 'resetDemographics'])->name('admin.restrictions.demographics.reset');
    Route::get('/restrictions/skills', [RestrictionController::class, 'skills'])->name('admin.restrictions.skills');
    Route::post('/restrictions/skills/update', [RestrictionController::class, 'updateSkills'])->name('admin.restrictions.skills.update');
    Route::post('/restrictions/skills/reset', [RestrictionController::class, 'resetSkills'])->name('admin.restrictions.skills.reset');
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
