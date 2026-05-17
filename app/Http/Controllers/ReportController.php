<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $query = Order::with('branch')->latest();

        if (! Auth::user()->isAdmin()) {
            $query->where('branch_id', Auth::user()->branch_id);
        } elseif ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('reports.sales', [
            'orders' => $orders,
            'branches' => Branch::orderBy('name')->get(),
            'grossSales' => (clone $query)->sum('total_amount'),
            'collected' => (clone $query)->sum('paid_amount'),
        ]);
    }
}
