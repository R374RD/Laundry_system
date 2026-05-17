<x-layouts.app heading="Dashboard" subheading="POS, order tracking, payments, and reports">
    <div class="grid grid-4">
        <div class="card"><div class="muted">Total Orders</div><div class="metric">{{ $totalOrders }}</div></div>
        <div class="card"><div class="muted">Pending</div><div class="metric">{{ $pendingOrders }}</div></div>
        <div class="card"><div class="muted">In Progress</div><div class="metric">{{ $activeOrders }}</div></div>
        <div class="card"><div class="muted">Sales Today</div><div class="metric">PHP {{ number_format($salesToday, 2) }}</div></div>
    </div>

    <div class="grid grid-2 top-space">
        <div class="card">
            <h2>Recent Orders</h2>
            <table>
                <thead><tr><th>Order ID</th><th>Branch</th><th>Status</th><th>Total</th><th></th></tr></thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a></td>
                            <td>{{ $order->branch->name }}</td>
                            <td><span class="badge">{{ \App\Models\Order::STATUSES[$order->status] }}</span></td>
                            <td>PHP {{ number_format($order->total_amount, 2) }}</td>
                            <td><a class="btn light" href="{{ route('orders.receipt', $order) }}" target="_blank">Receipt</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h2>System Activity</h2>
            <table>
                <thead><tr><th>User</th><th>Action</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ optional($log->user)->name ?? 'System' }}</td>
                            <td>{{ $log->action }}<br><span class="muted">{{ $log->details }}</span></td>
                            <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
