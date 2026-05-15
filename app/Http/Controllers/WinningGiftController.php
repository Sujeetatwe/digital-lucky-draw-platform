<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WinningGift;

class WinningGiftController extends Controller
{
    private function ensureAdmin()
    {
        if (auth()->check() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only Admin can manage gifts.');
        }
    }

    public function index()
    {
        $this->ensureAdmin();
        $gifts = WinningGift::latest()->get();
        // We will pass other data needed for other tabs here or use separate routes/ajax
        // For simplicity, let's load gifts here.
        // If we want tabs, we might want to return different views or one view with all data.
        // Let's stick to one view for now or sub-views.
        return view('winning_gifts.index', compact('gifts'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $request->validate([
            'gift_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        WinningGift::create($request->all());

        return redirect()->back()->with('success', 'Gift added successfully.');
    }

    public function update(Request $request, WinningGift $winningGift)
    {
        $this->ensureAdmin();
        $request->validate([
            'gift_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        $winningGift->update($request->all());

        return redirect()->back()->with('success', 'Gift updated successfully.');
    }

    public function destroy(WinningGift $winningGift)
    {
        $this->ensureAdmin();
        $winningGift->delete();
        return redirect()->back()->with('success', 'Gift deleted successfully.');
    }
}
