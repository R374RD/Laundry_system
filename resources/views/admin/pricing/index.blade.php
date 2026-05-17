<x-layouts.app heading="Global Pricing">
    <div class="grid grid-2">
        <div class="card">
            <h2>Current Price</h2>
            <div class="metric">PHP {{ number_format(optional($activePrice)->price_per_kilo ?? 0, 2) }}</div>
            <p class="muted">This price per kilo is used by all three branches.</p>
            <form method="POST" action="{{ route('admin.pricing.store') }}">
                @csrf
                <div class="field">
                    <label>New Price Per Kilo</label>
                    <input type="number" step="0.01" min="1" name="price_per_kilo" required>
                </div>
                <button class="btn" type="submit">Update Pricing</button>
            </form>
        </div>
        <div class="card">
            <h2>Price History</h2>
            <table>
                <thead><tr><th>Price</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @foreach($prices as $price)
                        <tr>
                            <td>PHP {{ number_format($price->price_per_kilo, 2) }}</td>
                            <td><span class="badge {{ $price->is_active ? 'good' : '' }}">{{ $price->is_active ? 'Active' : 'Old' }}</span></td>
                            <td>{{ $price->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
