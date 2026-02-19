<?php

namespace App\Http\Controllers;

use App\Models\Surplus;
use Illuminate\Http\Request;

class SurplusController extends Controller
{
    /**
     * Display a listing of the resource for the shop.
     */
    public function shopIndex()
    {
        // Fetch all surpluses that are not expired and have amount > 0
        $surpluses = Surplus::with('dessert')
            ->where('expiration_date', '>=', now())
            ->where('total_amount', '>', 0)
            ->orderBy('date', 'asc')
            ->get();

        return view('surpluses.shop', compact('surpluses'));
    }
}
