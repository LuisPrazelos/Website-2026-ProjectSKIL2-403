<?php

use App\Http\Controllers\SurplusController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Dessert;

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

    // User overview for deserts
    Route::get('/deserts', function () {
        $deserts = Dessert::with('picture')->get();
        return view('deserts.index', compact('deserts'));
    })->name('deserts.index');

    // Shopping Cart Page - Use full namespace for Livewire component
    Route::get('/shopping-cart', \App\Livewire\ShoppingCartPage::class)->name('shopping-cart');
    // Owner management view for recipes
    Route::get('/owner/recipes', [RecipeController::class, 'ownerIndex'])->name('owner.recipes.index');
    Route::post('/owner/recipes', [RecipeController::class, 'store'])->name('owner.recipes.store');
    Route::get('/owner/recipes/{recipe}', [RecipeController::class, 'show'])->name('owner.recipes.show');
    Route::get('/owner/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('owner.recipes.edit');
    Route::put('/owner/recipes/{recipe}', [RecipeController::class, 'update'])->name('owner.recipes.update');
    Route::delete('/owner/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('owner.recipes.destroy');

    Route::middleware([AdminMiddleware::class])->group(function () {
        // Owner management view for surpluses
        Route::get('/owner/surpluses', [SurplusController::class, 'ownerIndex'])->name('owner.surpluses.index');
        Route::post('/owner/surpluses', [SurplusController::class, 'store'])->name('owner.surpluses.store');

        // Owner management view for deserts
        Route::get('/owner/deserts', function () {
            $deserts = Dessert::with('picture')->paginate(10);
            return view('deserts.owner-index', compact('deserts'));
        })->name('owner.deserts.index');
    });
});

require __DIR__ . '/auth.php';
