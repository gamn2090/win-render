<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VendorNewPasswordController;
use App\Http\Controllers\Auth\VendorPasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredVendorController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/search', [UserController::class, 'searchVendorTypeGuest'])->name('guest.search.vendors');
    Route::get('admin/login', [AdminController::class, 'login']);
    Route::post('admin/login', [AdminController::class, 'loginRequest'])
                ->name('admin.login');
    Route::get('user/register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('user/register', [RegisteredUserController::class, 'store']);

    //Route::get('user/login', [AuthenticatedSessionController::class, 'create'])
    //            ->name('user.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::get('auth/google/redirect', [AuthenticatedSessionController::class, 'googleRedirect'])
                ->name('auth.google.redirect');
    Route::get('auth/google/callback', [AuthenticatedSessionController::class, 'googleCallback'])
                ->name('auth.google.callback');

    Route::get('login', function () {
        return view('welcome');
    });

    //Route::post('user/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('vendor/register', [RegisteredVendorController::class, 'create'])
                ->name('vendor.register');

    Route::get('vendor/register/form', [RegisteredVendorController::class, 'createSolo'])
    ->name('vendor.register.form');
    
    Route::get('vendor/ref/{id}', [RegisteredVendorController::class, 'createWithRef'])
                ->name('vendor.register.ref');
    
    Route::get('/ref/v/{referer}', [RegisteredVendorController::class, 'createWithReferral']);

    Route::get('ref/c/{referer}', [RegisteredUserController::class, 'createWithReferral']);

    Route::post('vendor/register', [RegisteredVendorController::class, 'store']);

    Route::get('vendor/login', [AuthenticatedSessionController::class, 'create'])
                ->name('vendor.login');

    Route::post('vendor/login', [AuthenticatedSessionController::class, 'vendorStore'])->name('vendor.login.post');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');

    // Vendor password reset (separate broker)
    Route::get('vendor/forgot-password', [VendorPasswordResetLinkController::class, 'create'])
                ->name('vendor.password.request');
    Route::post('vendor/forgot-password', [VendorPasswordResetLinkController::class, 'store'])
                ->name('vendor.password.email');
    Route::get('vendor/reset-password/{token}', [VendorNewPasswordController::class, 'create'])
                ->name('vendor.password.reset');
    Route::post('vendor/reset-password', [VendorNewPasswordController::class, 'store'])
                ->name('vendor.password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::post('/client/profile/notes', [ProfileController::class, 'updateOurWeddingDay'])
                ->name('profile.notes.edit');
});

Route::middleware('auth:vendor')->group(function () {
    Route::put('vendor/password', [PasswordController::class, 'updateVendor'])->name('vendor.password.update');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    Route::post('/admin/add/months', [AdminController::class, 'addMonths'])
        ->name('admin.add.months');
    Route::get('/admin/csv/vendors', [AdminController::class, 'generateVendorCSV'])
        ->name('admin.csv.vendors');
});
