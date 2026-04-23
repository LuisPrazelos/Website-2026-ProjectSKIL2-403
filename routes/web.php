<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\RecipeManager;
use App\Livewire\SurplusManager;
use App\Livewire\ShowRecipe;
use App\Livewire\EventRequest;
use App\Livewire\ThemeManager;
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
use App\Livewire\Ingredient as LivewireIngredient;
use App\Livewire\ManageReviews;
use App\Livewire\IngredientsManager;
use App\Models\Order;
use App\Models\Surplus;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Dessert;
use App\Livewire\Checkout;
use App\Livewire\WorkshopManager;
use App\Livewire\ShoppingCartPage;
use App\Livewire\ShowHappenings;
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

    // Route for the surplus shop (using Livewire SurplusManager)
    Route::get('/surplus-shop', SurplusManager::class)->name('userSurplusShop.index');

    // Cart routes (Using Livewire component)
    Route::get('shopping-cart', ShoppingCartPage::class)->name('shopping-cart');
    Route::get('cart', ShoppingCartPage::class)->name('cart.index');

    // Checkout route
    Route::get('checkout', Checkout::class)->name('checkout');

    // Payment route
    Route::get('payment', function () {
        return view('surpluses.payment');
    })->name('payment.page');

    // User overview for desserts
    Route::get('/deserts', function () {
        $deserts = Dessert::with('picture')->get();
        return view('deserts.index', compact('deserts'));
    })->name('deserts.index');

    // User event request route
    Route::get('/evenement-aanvragen', EventRequest::class)->name('event.request');

    // Owner management routes
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

        // Owner management view for workshops
        Route::get('/owner/workshops', WorkshopManager::class)->name('owner.workshops.index');
        // Ingredients (Using Livewire IngredientsManager)
        Route::get('/owner/ingredients', IngredientsManager::class)->name('owner.ingredients.index');

        // Desserts
        Route::get('/owner/deserts', function () {
            $deserts = Dessert::with('picture')->paginate(10);
            return view('deserts.owner-index', compact('deserts'));
        })->name('owner.deserts.index');

        Route::get('/owner/reviews', ManageReviews::class)->name('owner.reviews.index');


        // Surpluses (Using Livewire SurplusManager)
        Route::get('/owner/surpluses', SurplusManager::class)->name('owner.surpluses.index');
        Route::post('/owner/surpluses', SurplusManager::class)->name('owner.surpluses.store');
        Route::get('/owner/surpluses/{surplus}/edit', SurplusManager::class)->name('owner.surpluses.edit');
        Route::put('/owner/surpluses/{surplus}', SurplusManager::class)->name('owner.surpluses.update');
        Route::delete('/owner/surpluses/{surplus}', SurplusManager::class)->name('owner.surpluses.destroy');
        Route::get('/owner/themes', ThemeManager::class)->name('owner.themes.index');
    });
});

require __DIR__ . '/auth.php';
