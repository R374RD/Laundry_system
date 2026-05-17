<x-layouts.app heading="Order Tracking">
    <div class="card">
        <form method="GET" class="actions bottom-space">
            <input class="search-field" name="search" value="{{ request('search') }}" placeholder="Search Order ID or customer">
            <select class="filter-field" name="status">
                <option value="">All statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn" type="submit">Search</button>
            <a class="btn light" href="{{ route('orders.create') }}">New Order</a>
        </form>

        <table>
            <thead>
                <tr><th>Order ID</th><th>Customer</th><th>Branch</th><th>Status</th><th>Payment</th><th>Total</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}<br><span class="muted">{{ $order->customer_contact }}</span></td>
                        <td>{{ $order->branch->name }}</td>
                        <td><span class="badge">{{ $statuses[$order->status] }}</span></td>
                        <td><span class="badge {{ $order->payment_status === 'paid' ? 'good' : ($order->payment_status === 'partial' ? 'warn' : 'bad') }}">{{ ucfirst($order->payment_status) }}</span></td>
                        <td>PHP {{ number_format($order->total_amount, 2) }}</td>
                        <td><a class="btn light" href="{{ route('orders.show', $order) }}">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="top-space">{{ $orders->links() }}</div>
    </div>
</x-layouts.app>
