<x-layouts.app heading="Sales Report" subheading="Table-only sales monitoring per branch">
    <div class="card">
        <form method="GET" class="actions" style="margin-bottom:16px">
            @if(auth()->user()->isAdmin())
                <select style="max-width:220px" name="branch_id">
                    <option value="">All branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(request('branch_id') == $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
            @endif
            <input style="max-width:180px" type="date" name="from" value="{{ request('from') }}">
            <input style="max-width:180px" type="date" name="to" value="{{ request('to') }}">
            <button class="btn" type="submit">Filter</button>
        </form>
        <div class="actions" style="margin-bottom:16px">
            <span class="badge">Gross Sales: PHP {{ number_format($grossSales, 2) }}</span>
            <span class="badge good">Collected: PHP {{ number_format($collected, 2) }}</span>
        </div>
        <table>
            <thead><tr><th>Date</th><th>Order ID</th><th>Branch</th><th>Customer</th><th>Status</th><th>Total</th><th>Paid</th><th>Balance</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->branch->name }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</td>
                        <td>PHP {{ number_format($order->total_amount, 2) }}</td>
                        <td>PHP {{ number_format($order->paid_amount, 2) }}</td>
                        <td>PHP {{ number_format($order->balance(), 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8">No sales found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:16px">{{ $orders->links() }}</div>
    </div>
</x-layouts.app>
