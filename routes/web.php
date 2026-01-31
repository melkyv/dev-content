<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\LandingPage;
use App\Livewire\Subscription\Cancel;
use App\Livewire\Subscription\ManageSubscription;
use App\Livewire\Subscription\Success;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class)->name('landing-page');

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

Route::post('/webhooks/stripe', [\App\Http\Controllers\WebhookController::class, 'stripe'])->name('webhooks.stripe');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('contents')->group(function () {
        Route::get('/', function () {
            return view('livewire.contents.index');
        })->name('contents.index');

        Route::get('/create', function () {
            return view('livewire.contents.create');
        })->name('contents.create');

        Route::get('/my', function () {
            return view('livewire.contents.my');
        })->name('contents.my');
    });

    Route::get('/subscription', ManageSubscription::class)->name('subscription');

    Route::get('/subscription/success', Success::class)->name('subscription.success')->middleware('subscription.access');
    Route::get('/subscription/cancel', Cancel::class)->name('subscription.cancel')->middleware('subscription.access');

    Route::get('/profile', function () {
        return view('livewire.profile');
    })->name('profile');

    Route::get('/settings', function () {
        return view('livewire.settings');
    })->name('settings');
});
