<?php

namespace App\Http\Controllers;

use App\Models\Dessert;
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

    /**
     * Display a listing of the resource for the owner (management view).
     */
    public function ownerIndex(Request $request)
    {
        $search = $request->input('search');

        // For the owner we show all surpluses (paginated) with their related dessert
        // Filter by dessert name if search term is present
        $surpluses = Surplus::with('dessert')
            ->when($search, function ($query, $search) {
                $query->whereHas('dessert', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $desserts = Dessert::orderBy('name')->get();

        return view('surpluses.owner', compact('surpluses', 'desserts', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dessert' => 'required|exists:desserts,id',
            'date' => 'required|date|after_or_equal:today',
            'quantity' => 'required|integer|min:1',
            'discount' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        Surplus::create([
            'dessert_id' => $validated['dessert'],
            'date' => $validated['date'],
            'expiration_date' => $validated['date'],
            'total_amount' => $validated['quantity'],
            'sale' => $validated['discount'],
            'status' => 'available',
            'comment' => $validated['notes'],
        ]);

        return redirect()->route('owner.surpluses.index')->with('success', 'Overschot succesvol toegevoegd.');
    }
}
