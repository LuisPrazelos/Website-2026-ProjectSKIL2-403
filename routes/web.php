<?php

use App\Http\Controllers\SurplusController;
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
use App\Livewire\Ingredient as LivewireIngredient;
use App\Models\Order;
use App\Models\Surplus;
use App\Livewire\ManageReviews;
use App\Livewire\IngredientsManager;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Dessert;
use App\Livewire\ShowHappenings;
use App\Livewire\Checkout;
use App\Livewire\WorkshopManager;
use App\Livewire\WorkshopList; // Importeer WorkshopList
use App\Livewire\ShoppingCartPage;
use App\Livewire\SurplusManager;
use App\Livewire\ThemeManager;
use App\Http\Livewire\PackageManager;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

// Settings
Route::redirect('settings', 'settings/profile');
Route::get('settings/profile', Profile::class)->middleware('auth')->name('settings.profile');
Route::get('settings/password', Password::class)->middleware('auth')->name('settings.password');
Route::get('settings/appearance', Appearance::class)->middleware('auth')->name('settings.appearance');
Route::get('settings/two-factor', TwoFactor::class)->middleware(['auth', 'password.confirm'])->name('two-factor.show');

// Shop & Cart
Route::get('/surplus-shop', SurplusManager::class)->middleware('auth')->name('userSurplusShop.index');
Route::get('shopping-cart', ShoppingCartPage::class)->middleware('auth')->name('shopping-cart');
Route::get('cart', ShoppingCartPage::class)->middleware('auth')->name('cart.index');
Route::get('checkout', Checkout::class)->middleware('auth')->name('checkout');
Route::get('payment', fn () => view('surpluses.payment'))->middleware('auth')->name('payment.page');

// Desserts & Events
Route::get('/deserts', function () {
    $deserts = Dessert::with('picture')->get();
    return view('deserts.index', compact('deserts'));
})->middleware('auth')->name('deserts.index');

Route::get('/workshops', WorkshopList::class)->middleware('auth')->name('workshops.index'); // Toegevoegd

Route::get('/evenement-aanvragen', EventRequest::class)->middleware('auth')->name('event.request');

// Owner / Admin Management
Route::get('/owner/recipes', RecipeManager::class)->middleware('admin')->name('owner.recipes.index');
Route::get('/owner/recipes/{recipe}', ShowRecipe::class)->middleware('admin')->name('owner.recipes.show');
Route::get('/price-evolution', PriceEvolution::class)->middleware('admin')->name('price-evolution');
Route::get('/shopping-list', ShoppingList::class)->middleware('admin')->name('shopping-list');
Route::get('/owner/bestellingen', ManageOrders::class)->middleware('admin')->name('owner.orders.index');
Route::get('/owner/bestellingen/nieuw', CreateOrder::class)->middleware('admin')->name('owner.orders.create');
Route::get('/owner/bestellingen/{order}', OrderDetail::class)->middleware('admin')->name('owner.orders.show');
Route::get('/owner/aanvragen', RespondOrderRequests::class)->middleware('admin')->name('owner.respond-order-requests');
Route::get('/owner/aanvragen/{id}', ViewOrderRequest::class)->middleware('admin')->name('owner.respond-order-requests.view');
Route::get('/owner/aanvragen/{id}/beantwoorden', RespondToOrderRequest::class)->middleware('admin')->name('owner.respond-order-requests.respond');
Route::get('/owner/workshops', WorkshopManager::class)->middleware('admin')->name('owner.workshops.index');
Route::get('/owner/ingredients', IngredientsManager::class)->middleware('admin')->name('owner.ingredients.index');
Route::get('/owner/deserts', function () {
    $deserts = Dessert::with('picture')->paginate(10);
    return view('deserts.owner-index', compact('deserts'));
})->middleware('admin')->name('owner.deserts.index');
Route::get('/owner/reviews', ManageReviews::class)->middleware('admin')->name('owner.reviews.index');
Route::get('/owner/surpluses', SurplusManager::class)->middleware('admin')->name('owner.surpluses.index');
Route::get('/owner/themes', ThemeManager::class)->middleware('admin')->name('owner.themes.index');
Route::get('/owner/packages', PackageManager::class)->middleware('admin')->name('owner.packages.index');

require __DIR__ . '/auth.php';
