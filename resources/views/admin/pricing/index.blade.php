<x-layouts.app heading="Global Pricing">

    <div class="grid grid-2">

        {{-- CURRENT PRICING --}}
        <div class="card">
            <h2>Current Price</h2>

            <div class="metric">
                PHP {{ number_format(optional($activePrice)->price_per_load ?? 0, 2) }}
            </div>

            <p class="muted">
                This price per load is used by all branches.
            </p>

            <form method="POST" action="{{ route('admin.pricing.store') }}">
                @csrf

                <div class="field">
                    <label>Price Per Load</label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="price_per_load"
                           required>
                </div>

                <div class="field">
                    <label>Max Kilo Per Load</label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="max_kilo_per_load"
                           required>
                </div>

                <button class="btn" type="submit">
                    Update Pricing
                </button>
            </form>
        </div>

        {{-- HISTORY --}}
        <div class="card">
            <h2>Price History</h2>

            <table>
                <thead>
                    <tr>
                        <th>Price / Load</th>
                        <th>Max KG</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($prices as $price)
                        <tr>
                            <td>PHP {{ number_format($price->price_per_load, 2) }}</td>
                            <td>{{ $price->max_kilo_per_load }} kg</td>
                            <td>
                                <span class="badge {{ $price->is_active ? 'good' : '' }}">
                                    {{ $price->is_active ? 'Active' : 'Old' }}
                                </span>
                            </td>
                            <td>
                                {{ $price->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.app>