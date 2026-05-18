<x-layouts.app heading="Accept Laundry" subheading="Encode walk-in customer laundry details, calculate total, and receive payment">
    <form method="POST" action="{{ route('orders.store') }}" class="grid grid-2" data-order-form>
        @csrf
        <div class="card">
            <h2>Customer and Laundry Details</h2>
            <div class="field">
                <label>Customer Name</label>
                <input name="customer_name" value="{{ old('customer_name') }}" required>
            </div>
            <div class="field">
                <label>Contact Number</label>
                <input name="customer_contact" value="{{ old('customer_contact') }}">
            </div>
            @if(auth()->user()->isAdmin())
                <div class="field">
                    <label>Branch</label>
                    <select name="branch_id" required>
                        <option value="">Select branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="field">
                    <label>Branch</label>
                    <input value="{{ optional(auth()->user()->branch)->name }}" disabled>
                </div>
            @endif
            <div class="field">
                <label>Customer Email</label>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}">
            </div>
            <div class="field">
                <label>Weight in Kilos</label>
                <input type="number" step="0.01" min="0.1" name="weight_kg" value="{{ old('weight_kg') }}" data-weight-input required>
                <div class="muted">1 load covers up to {{ number_format(optional($pricing)->max_kilo_per_load ?? 0, 2) }} kg.</div>
            </div>
            <div class="field">
                <label>Price Per Load</label>
                <input value="PHP {{ number_format(optional($pricing)->price_per_load ?? 0, 2) }}" data-price-per-load="{{ optional($pricing)->price_per_load ?? 0 }}" data-max-kilo-per-load="{{ optional($pricing)->max_kilo_per_load ?? 0 }}" disabled>
            </div>
            <div class="field">
                <label>Estimated Loads</label>
                <input value="0" data-load-count disabled>
            </div>
            <div class="field">
                <label>Notes</label>
                <textarea name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="card">
            <h2>Add-On Services</h2>
            @forelse($addOns as $addOn)
                <label class="checkbox-row">
                    <input type="checkbox" name="add_ons[]" value="{{ $addOn->id }}" data-add-on-price="{{ $addOn->price }}" @checked(in_array($addOn->id, old('add_ons', [])))>
                    <span>{{ $addOn->name }} - PHP {{ number_format($addOn->price, 2) }}</span>
                </label>
            @empty
                <p class="muted">No active add-ons yet.</p>
            @endforelse

            <h2 class="section-title">Payment</h2>
            <div class="info-panel bottom-space">
                <div class="muted">Total Amount</div>
                <div class="metric" data-total-amount>PHP 0.00</div>
                <div class="muted" data-payment-message>Enter the amount paid by the customer.</div>
            </div>
            <div class="field">
                <label>Payment Type</label>
                <select name="payment_type" required data-payment-type>
                    <option value="full" @selected(old('payment_type', 'full') === 'full')>Full payment</option>
                    <option value="partial" @selected(old('payment_type') === 'partial')>Partial payment</option>
                    <option value="none" @selected(old('payment_type') === 'none')>No payment yet</option>
                </select>
            </div>
            <div class="field">
                <label>Customer Paid</label>
                <input type="number" step="0.01" min="0" name="payment_amount" value="{{ old('payment_amount') }}" data-payment-amount>
            </div>
            <div class="field">
                <label>Payment Method</label>
                <select name="payment_method">
                    <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                    <option value="gcash" @selected(old('payment_method') === 'gcash')>GCash</option>
                    <option value="card" @selected(old('payment_method') === 'card')>Card</option>
                    <option value="other" @selected(old('payment_method') === 'other')>Other</option>
                </select>
            </div>
            <button class="btn" type="submit">Create Order</button>
        </div>
    </form>
    <script>
        const weightInput = document.querySelector('[data-weight-input]');
        const priceInput = document.querySelector('[data-price-per-load]');
        const loadCountInput = document.querySelector('[data-load-count]');
        const addOnInputs = document.querySelectorAll('[data-add-on-price]');
        const totalAmount = document.querySelector('[data-total-amount]');
        const paymentType = document.querySelector('[data-payment-type]');
        const paymentAmount = document.querySelector('[data-payment-amount]');
        const paymentMessage = document.querySelector('[data-payment-message]');
        const pricePerLoad = Number(priceInput?.dataset.pricePerLoad || 0);
        const maxKiloPerLoad = Number(priceInput?.dataset.maxKiloPerLoad || 0);
        const peso = new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP',
        });

        function updateTotal() {
            const weight = Number(weightInput?.value || 0);
            const loadCount = weight > 0 && maxKiloPerLoad > 0
                ? Math.ceil(weight / maxKiloPerLoad)
                : 0;
            const addOnTotal = Array.from(addOnInputs).reduce((total, addOn) => {
                return addOn.checked ? total + Number(addOn.dataset.addOnPrice || 0) : total;
            }, 0);

            const total = (loadCount * pricePerLoad) + addOnTotal;
            const paid = Number(paymentAmount?.value || 0);

            if (loadCountInput) {
                loadCountInput.value = loadCount;
            }
            totalAmount.textContent = peso.format(total);

            if (paymentType?.value === 'none') {
                paymentMessage.textContent = `Order will be saved with ${peso.format(total)} balance.`;
                return;
            }

            if (paid <= 0) {
                paymentMessage.textContent = 'Enter the amount paid by the customer.';
                return;
            }

            if (paymentType?.value === 'full') {
                paymentMessage.textContent = paid >= total
                    ? `Validated. Change due: ${peso.format(Math.max(0, paid - total))}.`
                    : `Full payment needs ${peso.format(total - paid)} more.`;
                return;
            }

            paymentMessage.textContent = paid < total
                ? `Partial payment. Remaining balance: ${peso.format(total - paid)}.`
                : 'For the full amount or more, choose Full payment.';
        }

        weightInput?.addEventListener('input', updateTotal);
        paymentType?.addEventListener('change', updateTotal);
        paymentAmount?.addEventListener('input', updateTotal);
        addOnInputs.forEach((addOn) => addOn.addEventListener('change', updateTotal));
        updateTotal();
    </script>
</x-layouts.app>
