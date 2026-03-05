<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\SurplusController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Route for the surplus shop (for buying)
    Route::get('/surplus-shop', [SurplusController::class, 'shopIndex'])->name('userSurplusShop.index');

    // Cart routes
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout route
    Route::get('checkout', function() {
        // Placeholder for checkout logic
        return 'Checkout page';
    })->name('checkout');

    // Payment route
    Route::get('payment', function() {
        return view('surpluses.payment');
    })->name('payment.page');


    Route::middleware([AdminMiddleware::class])->group(function () {

        // Owner management view for surpluses
        Route::get('/owner/surpluses', [SurplusController::class, 'ownerIndex'])->name('owner.surpluses.index');
        Route::post('/owner/surpluses', [SurplusController::class, 'store'])->name('owner.surpluses.store');
    });
});

require __DIR__ . '/auth.php';
