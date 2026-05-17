<x-layouts.app heading="User Accounts">
    <div class="card bottom-space">
        <h2>Pending Staff Signups</h2>
        <p class="muted">When accepted, the temporary password is shown on this page for the admin to give to the staff member.</p>
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Branch</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($pendingUsers as $pending)
                    <tr>
                        <td>{{ $pending->name }}</td>
                        <td>{{ $pending->email }}</td>
                        <td>{{ optional($pending->branch)->name ?? 'No branch' }}</td>
                        <td>{{ $pending->created_at->format('M d, Y h:i A') }}</td>
                        <td class="actions">
                            <form method="POST" action="{{ route('admin.users.approve', $pending) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn" type="submit">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.deny', $pending) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn secondary" type="submit">Deny</button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $pending) }}" onsubmit="return confirm('Delete this signup request? This email can be used again after deletion.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn light" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No pending staff signup requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Create Account</h2>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="field"><label>Name</label><input name="name" required></div>
                <div class="field"><label>Email</label><input type="email" name="email" required></div>
                <div class="field">
                    <label>Temporary Password</label>
                    <input type="password" name="password" required>
                    <div class="muted">Staff must change this password on first login.</div>
                </div>
                <div class="field">
                    <label>Role</label>
                    <select name="role">
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="field">
                    <label>Branch</label>
                    <select name="branch_id">
                        <option value="">No branch / admin</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn" type="submit">Create User</button>
            </form>
        </div>
        <div class="card">
            <h2>Users</h2>
            <table>
                <thead><tr><th>User</th><th>Role</th><th>Branch</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <form id="update-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.update', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input class="field-gap" name="name" value="{{ $user->name }}" required>
                                    <input type="email" name="email" value="{{ $user->email }}" required>
                                    <input class="field-gap-top" type="password" name="password" placeholder="New password optional">
                                </form>
                            </td>
                            <td>
                                <select form="update-user-{{ $user->id }}" name="role">
                                    <option value="staff" @selected($user->role === 'staff')>Staff</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                            </td>
                            <td>
                                <select form="update-user-{{ $user->id }}" name="branch_id">
                                    <option value="">No branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" @selected($user->branch_id === $branch->id)>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <label class="checkbox-row">
                                    <input form="update-user-{{ $user->id }}" type="checkbox" name="is_active" value="1" @checked($user->is_active)>
                                    <span>Active</span>
                                </label>
                            </td>
                            <td class="actions">
                                <button class="btn light" form="update-user-{{ $user->id }}" type="submit">Save</button>
                                @if($user->id !== auth()->id())
                                    @if($user->role === 'staff')
                                        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" onsubmit="return confirm('Reset this staff password and force password change on next login?');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn light" type="submit">Reset Password</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user account? This cannot be undone, and the email can be used again.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn secondary" type="submit">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
