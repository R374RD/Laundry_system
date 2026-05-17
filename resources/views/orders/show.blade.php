<x-layouts.app heading="{{ $order->order_number }}" subheading="Order details, payment history, and status updates">
    <div class="actions bottom-space">
        <a class="btn" href="{{ route('orders.receipt', $order) }}" target="_blank">Print Receipt</a>
        <a class="btn light" href="{{ route('orders.index') }}">Back to Orders</a>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Order Details</h2>
            <table>
                <tr><th>Customer</th><td>{{ $order->customer_name }}<br><span class="muted">{{ $order->customer_contact }}</span></td></tr>
                <tr><th>Customer Email</th><td>{{ $order->customer_email ?: 'N/A' }}</td></tr>
                <tr><th>Branch</th><td>{{ $order->branch->name }}</td></tr>
                <tr><th>Created By</th><td>{{ $order->user->name }}</td></tr>
                <tr><th>Weight</th><td>{{ $order->weight_kg }} kg x PHP {{ number_format($order->price_per_kilo, 2) }}</td></tr>
                <tr><th>Add-ons</th><td>
                    @forelse($order->addOns as $addOn)
                        <div>{{ $addOn->name }} - PHP {{ number_format($addOn->pivot->price, 2) }}</div>
                    @empty
                        None
                    @endforelse
                </td></tr>
                <tr><th>Total</th><td>PHP {{ number_format($order->total_amount, 2) }}</td></tr>
                <tr><th>Paid</th><td>PHP {{ number_format($order->paid_amount, 2) }}</td></tr>
                <tr><th>Balance</th><td>PHP {{ number_format($order->balance(), 2) }}</td></tr>
            </table>
        </div>

        <div class="card">
            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                <h2>Update Status</h2>
                <form method="POST" action="{{ route('orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <div class="field">
                        <label>Status</label>
                        <select name="status">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn" type="submit">Save Status</button>
                </form>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
            <h2 class="section-title">Receive Payment</h2>
            @if($order->balance() > 0)
                <form method="POST" action="{{ route('payments.store', $order) }}">
                    @csrf
                    <div class="field">
                        <label>Payment Type</label>
                        <select name="payment_type" required>
                            <option value="partial">Partial payment</option>
                            <option value="full">Full payment</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Partial Payment Amount</label>
                        <input type="number" step="0.01" min="1" max="{{ $order->balance() }}" name="amount">
                        <div class="muted">For full payment, choose Full payment. The system will use the full balance automatically.</div>
                    </div>
                    <div class="field">
                        <label>Method</label>
                        <select name="method">
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="card">Card</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Remarks</label>
                        <textarea name="remarks" rows="3"></textarea>
                    </div>
                    <button class="btn" type="submit">Record Payment</button>
                </form>
            @else
                <p><span class="badge good">Fully Paid</span></p>
            @endif
            @else
                <h2>Payment Complete</h2>
                <p class="muted">This order is fully paid and ready for receipt printing.</p>
            @endif
        </div>
    </div>

    <div class="card top-space">
        <h2>Payment History</h2>
        <table>
            <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Received By</th><th>Remarks</th></tr></thead>
            <tbody>
                @forelse($order->payments as $payment)
                    <tr>
                        <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                        <td>PHP {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ strtoupper($payment->method) }}</td>
                        <td>{{ $payment->receiver->name }}</td>
                        <td>{{ $payment->remarks }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No payments recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
