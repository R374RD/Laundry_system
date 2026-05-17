<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt {{ $order->order_number }}</title>
    <style>
        :root {
            --color-brand: hsl(124, 74%, 25%);
            --color-brand-dark: hsl(125, 78%, 18%);
            --color-accent-soft: hsl(48, 100%, 93%);
            --color-canvas: hsl(96, 30%, 98%);
            --color-surface: hsl(0, 0%, 100%);
            --color-text-primary: hsl(214, 32%, 14%);
            --color-text-secondary: hsl(213, 18%, 42%);
            --color-border: hsl(104, 18%, 80%);
            --color-border-strong: hsl(103, 18%, 62%);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03);
            --radius-sm: 6px;
            --radius-md: 10px;
            --transition-fast: 100ms ease;
            --text-xs: 11px;
            --text-base: 15px;
            --text-lg: 20px;
        }
        body {
            font-family: "Segoe UI", Inter, system-ui, -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            color: var(--color-text-primary);
            margin: 0;
            background: var(--color-canvas);
            font-size: var(--text-base);
        }
        .receipt {
            width: 380px;
            max-width: calc(100% - 32px);
            margin: 24px auto;
            background: var(--color-surface);
            padding: 22px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border-top: 4px solid var(--color-brand);
        }
        .center { text-align: center; }
        .receipt-logo {
            width: 72px;
            height: 72px;
            border-radius: 9999px;
            object-fit: cover;
            display: block;
            margin: 0 auto 8px;
        }
        h1 { font-size: var(--text-lg); margin: 0; }
        .muted { color: var(--color-text-secondary); font-size: var(--text-xs); }
        .row { display: flex; justify-content: space-between; gap: 16px; margin: 8px 0; }
        .line { border-top: 1px dashed var(--color-border-strong); margin: 14px 0; }
        .total { font-weight: 700; font-size: 16px; }
        .btn {
            display: block;
            width: 380px;
            max-width: calc(100% - 32px);
            margin: 0 auto 16px;
            padding: 10px;
            border: 0;
            border-radius: var(--radius-sm);
            background: var(--color-brand);
            color: var(--color-surface);
            font-weight: 700;
            cursor: pointer;
            transition:
                background-color var(--transition-fast),
                box-shadow var(--transition-fast),
                transform var(--transition-fast);
        }
        .btn:hover { background: var(--color-brand-dark); box-shadow: var(--shadow-sm); transform: scale(1.01); }
        @media print {
            body { background: var(--color-surface); }
            .btn { display: none; }
            .receipt { width: 100%; margin: 0; border: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
    <button class="btn" onclick="window.print()">Print Receipt</button>
    <div class="receipt">
        <div class="center">
            <img class="receipt-logo" src="{{ asset('images/badeth-laundry-logo.png') }}" alt="Badeth Laundry Shop logo">
            <h1>Badeth Laundry Shop</h1>
            <div>{{ $order->branch->name }}</div>
            <div class="muted">{{ $order->created_at->format('M d, Y h:i A') }}</div>
        </div>

        <div class="line"></div>
        <div class="row"><span>Receipt / Order ID</span><strong>{{ $order->order_number }}</strong></div>
        <div class="row"><span>Customer</span><span>{{ $order->customer_name }}</span></div>
        <div class="row"><span>Contact</span><span>{{ $order->customer_contact ?: 'N/A' }}</span></div>
        <div class="row"><span>Email</span><span>{{ $order->customer_email ?: 'N/A' }}</span></div>
        <div class="row"><span>Cashier</span><span>{{ $order->user->name }}</span></div>

        <div class="line"></div>
        <div class="row">
            <span>Laundry {{ $order->weight_kg }} kg x PHP {{ number_format($order->price_per_kilo, 2) }}</span>
            <span>PHP {{ number_format($order->subtotal, 2) }}</span>
        </div>
        @foreach($order->addOns as $addOn)
            <div class="row">
                <span>{{ $addOn->name }}</span>
                <span>PHP {{ number_format($addOn->pivot->price, 2) }}</span>
            </div>
        @endforeach

        <div class="line"></div>
        <div class="row total"><span>Total</span><span>PHP {{ number_format($order->total_amount, 2) }}</span></div>
        <div class="row"><span>Paid</span><span>PHP {{ number_format($order->paid_amount, 2) }}</span></div>
        <div class="row"><span>Balance</span><span>PHP {{ number_format($order->balance(), 2) }}</span></div>
        <div class="row"><span>Payment Status</span><span>{{ strtoupper($order->payment_status) }}</span></div>
        <div class="row"><span>Order Status</span><span>{{ strtoupper(str_replace('_', ' ', $order->status)) }}</span></div>

        <div class="line"></div>
        <div class="center muted">Thank you for choosing our laundry service.</div>
    </div>
</body>
</html>
