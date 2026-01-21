<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class)->name('landing-page');

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('contents')->group(function () {
        Route::get('/', function () {
            return view('pages.contents.index');
        })->name('contents.index');

        Route::get('/create', function () {
            return view('pages.contents.create');
        })->name('contents.create');

        Route::get('/my', function () {
            return view('pages.contents.my');
        })->name('contents.my');
    });

    Route::get('/subscription', function () {
        return view('pages.subscription');
    })->name('subscription');

    Route::get('/profile', function () {
        return view('pages.profile');
    })->name('profile');

    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');
});
