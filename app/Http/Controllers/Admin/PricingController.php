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
            'price_per_kilo' => ['required', 'numeric', 'min:1'],
        ]);

        Pricing::query()->update(['is_active' => false]);
        Pricing::create(['price_per_kilo' => $data['price_per_kilo'], 'is_active' => true]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated global pricing',
            'details' => 'New price per kilo: ' . number_format($data['price_per_kilo'], 2),
        ]);

        return back()->with('success', 'Global price updated.');
    }
}
