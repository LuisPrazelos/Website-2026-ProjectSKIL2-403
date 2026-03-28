<?php

use App\Http\Controllers\CartController;
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
use App\Livewire\SurplusManager;

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
    Route::get('/surplus-shop', SurplusManager::class)->name('userSurplusShop.index');

    // Cart routes
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('shopping-cart', [CartController::class, 'index'])->name('shopping-cart');
    Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout route
    Route::get('checkout', Checkout::class)->name('checkout');

    // Payment route
    Route::get('payment', function () {
        return view('surpluses.payment');
    })->name('payment.page');

    // User overview for deserts
    Route::get('/deserts', function () {
        $deserts = Dessert::with('picture')->get(); // Eager load the picture relationship
        return view('deserts.index', compact('deserts'));
    })->name('deserts.index');

    // User event request route
    Route::get('/evenement-aanvragen', EventRequest::class)->name('event.request');

    Route::middleware([AdminMiddleware::class])->group(function () {
        // Livewire routes for recipe management
        Route::get('/owner/recipes', RecipeManager::class)->name('owner.recipes.index');
        Route::get('/owner/recipes/{recipe}', ShowRecipe::class)->name('owner.recipes.show');

        // Route voor prijsontwikkeling
        Route::get('/price-evolution', PriceEvolution::class)->name('price-evolution');

        // Route voor boodschappenlijst
        Route::get('/shopping-list', ShoppingList::class)->name('shopping-list');
        // Route voor bestellingen beheren
        Route::get('/owner/bestellingen', ManageOrders::class)->name('owner.orders.index');
        Route::get('/owner/bestellingen/nieuw', CreateOrder::class)->name('owner.orders.create');
        Route::get('/owner/bestellingen/{order}', OrderDetail::class)->name('owner.orders.show');
        Route::get('/owner/aanvragen', RespondOrderRequests::class)->name('owner.respond-order-requests');
        Route::get('/owner/aanvragen/{id}', ViewOrderRequest::class)->name('owner.respond-order-requests.view');
        Route::get('/owner/aanvragen/{id}/beantwoorden', RespondToOrderRequest::class)->name('owner.respond-order-requests.respond');

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

        // Owner management view for surpluses
        Route::get('/owner/surpluses', SurplusManager::class)->name('owner.surpluses.index');

        // Owner management view for happenings
        Route::get('/owner/happenings', ShowHappenings::class)->name('owner.happenings.index');
    });
});

require __DIR__ . '/auth.php';
