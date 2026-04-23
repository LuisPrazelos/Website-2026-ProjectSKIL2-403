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
use App\Models\Order;
use App\Models\Surplus;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Dessert;
use App\Livewire\ShowHappenings;
use App\Livewire\Checkout;
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
    Route::get('/surplus-shop', SurplusManager::class)->name('userSurplusShop.index');

    // Cart routes implemented with closures
    Route::get('cart', function () {
        return view('surpluses.cart');
    })->name('cart.index');

    Route::get('shopping-cart', function () {
        return view('surpluses.cart');
    })->name('shopping-cart');

    Route::post('cart/add/{id}', function (Request $request, $id) {
        $surplus = Surplus::with('dessert')->findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);
        $quantity = max(1, min($quantity, $surplus->total_amount));

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
            $cart[$id]['quantity'] = min($cart[$id]['quantity'], $surplus->total_amount);
        } else {
            $cart[$id] = [
                "dessert_name" => $surplus->dessert->name,
                "surplus_id" => $surplus->id,
                "quantity" => $quantity,
                "price" => $surplus->dessert->price,
                "discount" => $surplus->sale,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product toegevoegd aan winkelwagen!');
    })->name('cart.add');

    Route::patch('cart/update/{id}', function (Request $request, $id) {
        $quantity = (int) $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            if($quantity <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Product verwijderd uit winkelwagen!');
            } else {
                $cart[$id]['quantity'] = $quantity;
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Hoeveelheid bijgewerkt!');
            }
        }
        return redirect()->back()->with('error', 'Product niet gevonden in winkelwagen!');
    })->name('cart.update');

    Route::delete('cart/remove/{id}', function ($id) {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Product verwijderd uit winkelwagen!');
    })->name('cart.remove');

    // Checkout route
    Route::get('checkout', Checkout::class)->name('checkout');

    // Payment route
    Route::get('payment', function () {
        return view('surpluses.payment');
    })->name('payment.page');

    // User overview for deserts
    Route::get('/deserts', function () {
        $deserts = Dessert::with('picture')->where('is_available', true)->get(); // Only show available desserts

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
        Route::get('/owner/ingredients', LivewireIngredient::class)->name('owner.ingredients.index');
        Route::get('/owner/deserts', function () {
            $deserts = Dessert::with('picture')->paginate(10);
            return view('deserts.owner-index', compact('deserts'));
        })->name('owner.deserts.index');
        Route::get('/owner/surpluses', SurplusManager::class)->name('owner.surpluses.index');
        Route::post('/owner/surpluses', SurplusManager::class)->name('owner.surpluses.store');
        Route::get('/owner/surpluses/{surplus}/edit', SurplusManager::class)->name('owner.surpluses.edit');
        Route::put('/owner/surpluses/{surplus}', SurplusManager::class)->name('owner.surpluses.update');
        Route::delete('/owner/surpluses/{surplus}', SurplusManager::class)->name('owner.surpluses.destroy');
        Route::get('/owner/happenings', ShowHappenings::class)->name('owner.happenings.index');
        Route::get('/owner/thema', ThemeManager::class)->name('owner.themes.index');
    });
});

require __DIR__ . '/auth.php';
