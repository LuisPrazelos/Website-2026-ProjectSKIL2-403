<?php

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SurplusController;
use App\Http\Controllers\IngredientController; // Import the new controller
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Ingredient;
use App\Models\PriceEvolution;
use App\Models\Dessert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $deserts = Dessert::with('picture')->get(); // Eager load the picture relationship
        return view('deserts.index', compact('deserts'));
    })->name('deserts.index');

    Route::middleware([AdminMiddleware::class])->group(function () {
        // Owner management view for surpluses
        Route::get('/owner/surpluses', [SurplusController::class, 'ownerIndex'])->name('owner.surpluses.index');
        Route::post('/owner/surpluses', [SurplusController::class, 'store'])->name('owner.surpluses.store');

        // Owner management view for ingredients
        Route::get('/owner/ingredients', [IngredientController::class, 'ownerIndex'])->name('owner.ingredients.index');
        Route::post('/owner/ingredients', [IngredientController::class, 'store'])->name('owner.ingredients.store');
        Route::get('/owner/ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('owner.ingredients.edit');
        Route::put('/owner/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('owner.ingredients.update');
        Route::delete('/owner/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('owner.ingredients.destroy');
        // Owner management view for deserts
        Route::get('/owner/deserts', function () {
            $deserts = Dessert::with('picture')->paginate(10); // Paginate for better performance
            return view('deserts.owner-index', compact('deserts'));
        })->name('owner.deserts.index');
    });
});

require __DIR__ . '/auth.php';
