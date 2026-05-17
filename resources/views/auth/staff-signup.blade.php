<x-layouts.app title="Staff Sign Up">
    <div class="login">
        <div class="card">
            <div class="brand-wrap auth-brand">
                <div class="brand-mark">L</div>
                <div>
                    <h1 class="flush-title">Staff Sign Up</h1>
                    <p class="muted flush-copy">Submit your account request for admin approval.</p>
                </div>
            </div>
            @include('layouts.flash')
            <form method="POST" action="{{ route('staff.signup.store') }}">
                @csrf
                <div class="field">
                    <label>Name</label>
                    <input name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="field">
                    <label>Branch</label>
                    <select name="branch_id" required>
                        <option value="">Select branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Submit Request</button>
                    <a class="btn light" href="{{ route('login') }}">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
