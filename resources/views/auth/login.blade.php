<x-layouts.app title="Login">
    <div class="login">
        <div class="card">
            <div class="brand-wrap auth-brand">
                <div class="brand-mark">
                    <img class="brand-logo" src="{{ asset('images/badeth-laundry-logo.png') }}" alt="Badeth Laundry Shop logo">
                </div>
                <div>
                    <h1 class="flush-title">Badeth Laundry Shop</h1>
                    <p class="muted flush-copy">Clean order tracking for every laundry branch.</p>
                </div>
            </div>
            @include('layouts.flash')
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button class="btn" type="submit">Login</button>
            </form>
            <p class="top-space"><a class="btn light" href="{{ route('staff.signup') }}">Staff Sign Up</a></p>
            <div class="info-panel top-space">
                <p class="muted copy-line">Admin: admin@laundry.test / password</p>
                <p class="muted copy-line">Staff: main@laundry.test / password</p>
            </div>
        </div>
    </div>
</x-layouts.app>
