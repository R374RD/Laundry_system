<x-layouts.app heading="Change Password" subheading="{{ auth()->user()->must_change_password ? 'Temporary passwords must be changed before using the system' : 'Update your account password' }}">
    <div class="card narrow-panel">
        <h2>{{ auth()->user()->must_change_password ? 'Set New Password' : 'Change My Password' }}</h2>
        <form method="POST" action="{{ route('password.change.store') }}">
            @csrf
            @if(! auth()->user()->must_change_password)
                <div class="field">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
            @endif
            <div class="field">
                <label>New Password</label>
                <input type="password" name="password" required>
                <div class="muted">Minimum of 8 characters.</div>
            </div>
            <div class="field">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button class="btn" type="submit">Save Password</button>
        </form>
    </div>
</x-layouts.app>
