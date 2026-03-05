<?php

namespace App\Http\Controllers;

use App\Models\Surplus;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('surpluses.cart');
    }

    public function add(Request $request, $id)
    {
        $surplus = Surplus::with('dessert')->findOrFail($id);

        // Validate quantity input
        $quantity = (int) $request->input('quantity', 1);
        $quantity = max(1, min($quantity, $surplus->total_amount));

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            // If item already in cart, add to quantity
            $cart[$id]['quantity'] += $quantity;
            // Ensure we don't exceed available amount
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
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product verwijderd uit winkelwagen!');
    }

    public function update(Request $request, $id)
    {
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
    }
}
