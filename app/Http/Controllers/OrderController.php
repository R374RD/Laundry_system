<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AddOnService;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['branch', 'user', 'addOns']);

        if (! Auth::user()->isAdmin()) {
            $query->where('branch_id', Auth::user()->branch_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($inner) use ($request) {
                $inner->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('orders.index', [
            'orders' => $query->latest()->paginate(12)->withQueryString(),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function create()
    {
        return view('orders.create', [
            'pricing' => Pricing::where('is_active', true)->latest()->first(),
            'addOns' => AddOnService::where('is_active', true)->orderBy('name')->get(),
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_contact' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'branch_id' => Auth::user()->isAdmin() ? ['required', 'exists:branches,id'] : ['nullable'],
            'weight_kg' => ['required', 'numeric', 'min:0.1'],
            'add_ons' => ['nullable', 'array'],
            'add_ons.*' => ['exists:add_on_services,id'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_type' => ['required', 'in:none,partial,full'],
            'payment_method' => ['required_unless:payment_type,none', 'in:cash,gcash,card,other'],
            'notes' => ['nullable', 'string'],
        ]);

        $pricing = Pricing::where('is_active', true)->latest()->firstOrFail();
        $selectedAddOns = AddOnService::whereIn('id', $data['add_ons'] ?? [])->get();

        $subtotal = round($data['weight_kg'] * $pricing->price_per_kilo, 2);
        $addOnTotal = $selectedAddOns->sum('price');
        $total = $subtotal + $addOnTotal;
        $paymentAmount = $this->resolveInitialPayment($data, $total);
        $branchId = Auth::user()->isStaff() ? Auth::user()->branch_id : $data['branch_id'];
        $customerEmail = $data['customer_email'] ?? null;

        $order = DB::transaction(function () use ($data, $pricing, $selectedAddOns, $subtotal, $addOnTotal, $total, $paymentAmount, $branchId, $customerEmail) {
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) (Order::max('id') + 1), 4, '0', STR_PAD_LEFT),
                'branch_id' => $branchId,
                'user_id' => Auth::id(),
                'customer_id' => null,
                'customer_name' => $data['customer_name'],
                'customer_contact' => $data['customer_contact'] ?? null,
                'customer_email' => $customerEmail,
                'weight_kg' => $data['weight_kg'],
                'price_per_kilo' => $pricing->price_per_kilo,
                'subtotal' => $subtotal,
                'add_on_total' => $addOnTotal,
                'total_amount' => $total,
                'paid_amount' => $paymentAmount,
                'payment_status' => $this->paymentStatus($paymentAmount, $total),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($selectedAddOns as $addOn) {
                $order->addOns()->attach($addOn->id, ['price' => $addOn->price]);
            }

            if ($paymentAmount > 0) {
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $paymentAmount,
                    'method' => $data['payment_method'] ?? 'cash',
                    'received_by' => Auth::id(),
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created order',
                'details' => $order->order_number,
            ]);

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Laundry order created.');
    }

    public function show(Order $order)
    {
        $this->authorizeBranch($order);

        return view('orders.show', [
            'order' => $order->load(['branch', 'user', 'addOns', 'payments.receiver']),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function receipt(Order $order)
    {
        $this->authorizeBranch($order);

        return view('orders.receipt', [
            'order' => $order->load(['branch', 'user', 'addOns', 'payments.receiver']),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeBranch($order);

        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::STATUSES))],
        ]);

        $order->update(['status' => $data['status']]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Updated order status',
            'details' => $order->order_number . ' to ' . Order::STATUSES[$data['status']],
        ]);

        return back()->with('success', 'Order status updated.');
    }

    private function paymentStatus(float $paid, float $total): string
    {
        if ($paid >= $total) {
            return 'paid';
        }

        return $paid > 0 ? 'partial' : 'unpaid';
    }

    private function resolveInitialPayment(array $data, float $total): float
    {
        if ($data['payment_type'] === 'none') {
            return 0;
        }

        $amount = (float) ($data['payment_amount'] ?? 0);

        if ($data['payment_type'] === 'full') {
            if ($amount < $total) {
                back()->withErrors([
                    'payment_amount' => 'Customer paid amount must be at least the total amount for a full payment.',
                ])->withInput()->throwResponse();
            }

            return $total;
        }

        if ($amount <= 0 || $amount >= $total) {
            back()->withErrors([
                'payment_amount' => 'Partial payment must be greater than 0 and less than the total amount.',
            ])->withInput()->throwResponse();
        }

        return $amount;
    }

    private function authorizeBranch(Order $order): void
    {
        abort_if(! Auth::user()->isAdmin() && $order->branch_id !== Auth::user()->branch_id, 403);
    }

}
