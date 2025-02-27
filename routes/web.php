<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\VerifyEmailController;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\PasswordResetRequest;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\TwoFactorChallenge;
use App\Livewire\Auth\Verification;
use Illuminate\Support\Facades\Route;


Route::redirect('login', 'auth/login')->name('login');
Route::redirect('register', 'auth/register')->name('register');

Route::get('/', function () {
    return view('welcome');
})->middleware('verified', 'password.confirm');

Route::get('/user/two-factor-authentication', \App\Livewire\TwoFactorAuthentication::class)
    ->middleware('auth')
    ->name('user.two-factor-authentication');

Route::prefix(config('fortify.prefix', 'auth'))->group(function () {
    // Login & Register Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', Login::class)->name('auth.login');
        Route::get('/register', Register::class)->name('auth.register');
    });

    //Password Reset Page
    Route::get('/password/{token}', ResetPassword::class)->name('password.reset');
    //Password Confirm
    Route::get('/confirm', ConfirmPassword::class)
        ->middleware('auth')
        ->name('password.confirm');
    //Password reset Request
    Route::get('/reset', PasswordResetRequest::class)->name('auth.password.request');

    // Two-Factor Authentication Challenge
    Route::get('/two-factor-challenge', TwoFactorChallenge::class)
        ->middleware(['two-factor-challenged', 'throttle:5,1'])
        ->name('auth.two-factor-challenge');

    Route::middleware(['auth', 'web'])->group(function () {
        Route::post('/logout', LogoutController::class)->name('logout');
        //Send verification email
        Route::get('verify', Verification::class)
            ->middleware('auth')
            ->name('verification.notice');
        //handle verification link
        Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'auth', 'throttle:6,1'])
            ->name('verification.verify');
        Route::get('/logout', [LogoutController::class, 'getLogout'])->name('logout.get');
    });

    Route::middleware(['web'])->group(function () {
        // Add social redirect and callback routes
        Route::get('/{driver}/redirect', [SocialController::class, 'redirect']);
        Route::get('/{driver}/callback', [SocialController::class, 'callback']);
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
