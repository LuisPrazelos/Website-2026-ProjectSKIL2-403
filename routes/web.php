<?php

use App\Http\Controllers\SurplusController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Ingredient;
use App\Models\PriceEvolution;
use Illuminate\Http\Request;

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

    // Owner management view for surpluses
    Route::get('/owner/surpluses', [SurplusController::class, 'ownerIndex'])->name('owner.surpluses.index');
    Route::post('/owner/surpluses', [SurplusController::class, 'store'])->name('owner.surpluses.store');

    Route::get('/price-evolution', function (Request $request) {
        $ingredientId = $request->input('ingredient');
        $priceEvolutions = null;
        $ingredientName = null;
        $ingredients = Ingredient::orderBy('ingredientName')->get();

        if ($ingredientId) {
            $ingredient = Ingredient::find($ingredientId);
            if ($ingredient) {
                $ingredientName = $ingredient->ingredientName;
                $priceEvolutions = PriceEvolution::where('ingredientId', $ingredient->ingredientId)
                    ->orderBy('date', 'asc')
                    ->get();
            }
        }

        return view('price-evolution', [
            'ingredients' => $ingredients,
            'priceEvolutions' => $priceEvolutions,
            'ingredientName' => $ingredientName,
            'selectedIngredient' => $ingredientId,
        ]);
    })->middleware('admin')->name('price-evolution'); // Voeg de 'admin' middleware toe
});

require __DIR__.'/auth.php';
