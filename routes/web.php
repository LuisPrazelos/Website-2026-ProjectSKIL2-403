<?php

use App\Http\Controllers\SurplusController;
use App\Http\Controllers\IngredientController;
use App\Http\Middleware\AdminMiddleware;
use App\Livewire\RecipeManager;
use App\Livewire\ShowRecipe;
use App\Livewire\EventRequest;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\ShoppingList;
use App\Livewire\PriceEvolution;
use App\Livewire\Orders\ManageOrders;
use App\Livewire\Orders\OrderDetail;
use App\Livewire\Orders\CreateOrder;
use App\Livewire\Orders\RespondOrderRequests;
use App\Livewire\Orders\ViewOrderRequest;
use App\Livewire\Orders\RespondToOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Dessert;
use App\Livewire\ShowHappenings;
use App\Livewire\Checkout;
use App\Livewire\ShoppingCartPage;

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

    // Cart routes (Now using Livewire component)
    Route::get('shopping-cart', ShoppingCartPage::class)->name('shopping-cart');
    Route::get('cart', ShoppingCartPage::class)->name('cart.index');

    // Checkout route
    Route::get('checkout', Checkout::class)->name('checkout');

    // Payment route
    Route::get('payment', function () {
        return view('surpluses.payment');
    })->name('payment.page');

    // User overview for deserts
    Route::get('/deserts', function () {
        $deserts = Dessert::with('picture', 'measurementUnit')->where('is_available', true)->get();
        return view('deserts.index', compact('deserts'));
    })->name('deserts.index');

    // User event request route
    Route::get('/evenement-aanvragen', EventRequest::class)->name('event.request');

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/owner/recipes', RecipeManager::class)->name('owner.recipes.index');
        Route::get('/owner/recipes/{recipe}', ShowRecipe::class)->name('owner.recipes.show');
        Route::get('/price-evolution', PriceEvolution::class)->name('price-evolution');
        Route::get('/shopping-list', ShoppingList::class)->name('shopping-list');
        Route::get('/owner/bestellingen', ManageOrders::class)->name('owner.orders.index');
        Route::get('/owner/bestellingen/nieuw', CreateOrder::class)->name('owner.orders.create');
        Route::get('/owner/bestellingen/{order}', OrderDetail::class)->name('owner.orders.show');
        Route::get('/owner/aanvragen', RespondOrderRequests::class)->name('owner.respond-order-requests');
        Route::get('/owner/aanvragen/{id}', ViewOrderRequest::class)->name('owner.respond-order-requests.view');
        Route::get('/owner/aanvragen/{id}/beantwoorden', RespondToOrderRequest::class)->name('owner.respond-order-requests.respond');

        Route::get('/owner/ingredients', [IngredientController::class, 'ownerIndex'])->name('owner.ingredients.index');
        Route::post('/owner/ingredients', [IngredientController::class, 'store'])->name('owner.ingredients.store');
        Route::get('/owner/ingredients/{ingredient}/edit', [IngredientController::class, 'edit'])->name('owner.ingredients.edit');
        Route::put('/owner/ingredients/{ingredient}', [IngredientController::class, 'update'])->name('owner.ingredients.update');
        Route::delete('/owner/ingredients/{ingredient}', [IngredientController::class, 'destroy'])->name('owner.ingredients.destroy');

        Route::get('/owner/deserts', function () {
            $deserts = Dessert::with('picture')->paginate(10);
            return view('deserts.owner-index', compact('deserts'));
        })->name('owner.deserts.index');

        Route::get('/owner/surpluses', [SurplusController::class, 'ownerIndex'])->name('owner.surpluses.index');
        Route::post('/owner/surpluses', [SurplusController::class, 'store'])->name('owner.surpluses.store');
        Route::get('/owner/surpluses/{surplus}/edit', [SurplusController::class, 'edit'])->name('owner.surpluses.edit');
        Route::put('/owner/surpluses/{surplus}', [SurplusController::class, 'update'])->name('owner.surpluses.update');
        Route::delete('/owner/surpluses/{surplus}', [SurplusController::class, 'destroy'])->name('owner.surpluses.destroy');

        Route::get('/owner/happenings', ShowHappenings::class)->name('owner.happenings.index');
    });
});

require __DIR__ . '/auth.php';
