<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::query();
        $payments = Payment::query();

        if (! $user->isAdmin()) {
            $orders->where('branch_id', $user->branch_id);
            $payments->whereHas('order', fn ($query) => $query->where('branch_id', $user->branch_id));
        }

        return view('dashboard.index', [
            'totalOrders' => (clone $orders)->count(),
            'pendingOrders' => (clone $orders)->where('status', 'pending')->count(),
            'activeOrders' => (clone $orders)->whereIn('status', ['washing', 'drying', 'ironing', 'ready_for_pickup'])->count(),
            'completedOrders' => (clone $orders)->where('status', 'claimed')->count(),
            'totalBalance' => (clone $orders)->get()->sum(fn (Order $order) => $order->balance()),
            'salesToday' => (clone $payments)->whereDate('created_at', today())->sum('amount'),
            'recentOrders' => (clone $orders)->with(['branch', 'user'])->latest()->take(8)->get(),
            'logs' => ActivityLog::with('user')->latest()->take(8)->get(),
        ]);
    }
}
