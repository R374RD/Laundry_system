<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PricingController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('admin.pricing.index', [
            'prices' => Pricing::latest()->get(),
            'activePrice' => Pricing::where('is_active', true)->latest()->first(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'price_per_load' => ['required', 'numeric', 'min:1'],
            'max_kilo_per_load' => ['required', 'numeric', 'min:1'],
        ]);

        // deactivate old pricing
        Pricing::where('is_active', true)->update([
            'is_active' => false
        ]);

        // create new active pricing
        $pricing = Pricing::create([
            'price_per_load' => $data['price_per_load'],
            'max_kilo_per_load' => $data['max_kilo_per_load'],
            'is_active' => true
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated global pricing',
            'details' =>
                'New price per load: ' . number_format($pricing->price_per_load, 2) .
                ' | Max KG: ' . number_format($pricing->max_kilo_per_load, 2)
        ]);

        return back()->with('success', 'Global pricing updated.');
    }
}