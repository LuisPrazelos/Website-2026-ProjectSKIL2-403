<?php

namespace App\Http\Controllers;

use App\Models\Dessert;
use App\Models\Surplus;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Eager load relationships for cart items if needed for display
        $cart = session()->get('cart', []);
        $detailedCart = [];

        foreach ($cart as $cartKey => $item) {
            if ($item['type'] === 'dessert') {
                $dessert = Dessert::with('measurementUnit')->find($item['id']);
                if ($dessert) {
                    $detailedCart[$cartKey] = [
                        'type' => 'dessert',
                        'id' => $dessert->id,
                        'name' => $dessert->name,
                        'quantity' => $item['quantity'],
                        'price' => $dessert->price,
                        'portion_size' => $dessert->portion_size,
                        'measurement_unit' => $dessert->measurementUnit?->name,
                    ];
                }
            } elseif ($item['type'] === 'surplus') {
                $surplus = Surplus::with('dessert.measurementUnit')->find($item['id']);
                if ($surplus) {
                    $detailedCart[$cartKey] = [
                        'type' => 'surplus',
                        'id' => $surplus->id,
                        'name' => $surplus->dessert->name . ' (Surplus)',
                        'quantity' => $item['quantity'],
                        'price' => $surplus->dessert->price,
                        'discount' => $surplus->sale,
                        'available_amount' => $surplus->total_amount, // For validation in update
                        'portion_size' => $surplus->dessert->portion_size,
                        'measurement_unit' => $surplus->dessert->measurementUnit?->name,
                    ];
                }
            }
        }

        return view('surpluses.cart', ['cart' => $detailedCart]);
    }

    public function addDessert(Request $request, Dessert $dessert)
    {
        $quantity = (int) $request->input('quantity', 1);
        $quantity = max(1, $quantity); // Ensure quantity is at least 1

        $cart = session()->get('cart', []);
        $cartKey = 'dessert_' . $dessert->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'type' => 'dessert',
                'id' => $dessert->id,
                'name' => $dessert->name,
                'quantity' => $quantity,
                'price' => $dessert->price,
                'portion_size' => $dessert->portion_size,
                'measurement_unit' => $dessert->measurementUnit?->name,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Dessert toegevoegd aan winkelwagen!');
    }

    public function addSurplus(Request $request, Surplus $surplus)
    {
        $quantity = (int) $request->input('quantity', 1);
        $quantity = max(1, min($quantity, $surplus->total_amount)); // Validate against available surplus amount

        $cart = session()->get('cart', []);
        $cartKey = 'surplus_' . $surplus->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['quantity'] = min($cart[$cartKey]['quantity'], $surplus->total_amount); // Re-validate
        } else {
            $cart[$cartKey] = [
                'type' => 'surplus',
                'id' => $surplus->id,
                'name' => $surplus->dessert->name . ' (Surplus)',
                'quantity' => $quantity,
                'price' => $surplus->dessert->price,
                'discount' => $surplus->sale,
                'portion_size' => $surplus->dessert->portion_size,
                'measurement_unit' => $surplus->dessert->measurementUnit?->name,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Surplus toegevoegd aan winkelwagen!');
    }

    public function remove($cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product verwijderd uit winkelwagen!');
        }

        return redirect()->back()->with('error', 'Product niet gevonden in winkelwagen!');
    }

    public function update(Request $request, $cartKey)
    {
        $quantity = (int) $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            if ($quantity <= 0) {
                unset($cart[$cartKey]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Product verwijderd uit winkelwagen!');
            } else {
                // If it's a surplus, validate against available amount
                if (str_starts_with($cartKey, 'surplus_')) {
                    $surplusId = (int) str_replace('surplus_', '', $cartKey);
                    $surplus = Surplus::find($surplusId);
                    if ($surplus) {
                        $quantity = min($quantity, $surplus->total_amount);
                    }
                }
                $cart[$cartKey]['quantity'] = $quantity;
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Hoeveelheid bijgewerkt!');
            }
        }

        return redirect()->back()->with('error', 'Product niet gevonden in winkelwagen!');
    }
}
