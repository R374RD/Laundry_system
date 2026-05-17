<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_if(! Auth::user()->isAdmin() && $order->branch_id !== Auth::user()->branch_id, 403);

        $data = $request->validate([
            'payment_type' => ['required', 'in:partial,full'],
            'amount' => ['nullable', 'numeric', 'min:1', 'max:' . $order->balance()],
            'method' => ['required', 'in:cash,gcash,card,other'],
            'remarks' => ['nullable', 'string'],
        ]);

        if ($data['payment_type'] === 'full') {
            $data['amount'] = $order->balance();
        } elseif (blank($data['amount'] ?? null)) {
            return back()->withErrors([
                'amount' => 'Enter an amount for partial payment.',
            ])->withInput();
        } elseif (($data['amount'] ?? 0) >= $order->balance()) {
            return back()->withErrors([
                'amount' => 'Use Full Payment when paying the whole remaining balance.',
            ])->withInput();
        }

        DB::transaction(function () use ($order, $data) {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'remarks' => $data['remarks'] ?? null,
                'received_by' => Auth::id(),
            ]);

            $paid = $order->paid_amount + $data['amount'];

            $order->update([
                'paid_amount' => $paid,
                'payment_status' => $paid >= $order->total_amount ? 'paid' : 'partial',
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Recorded payment',
                'details' => $order->order_number . ' received ' . number_format($data['amount'], 2),
            ]);
        });

        return back()->with('success', 'Payment recorded.');
    }
}
