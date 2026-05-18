<x-layouts.app heading="User Accounts">

    <div class="page-stack">

        {{-- PENDING USERS --}}
        <div class="card bottom-space">
            <h2>Pending Staff Signups</h2>

            <p class="muted">
                When accepted, the temporary password is shown on this page for the admin to give to the staff member.
            </p>

            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Branch</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pendingUsers as $pending)
                        <tr>
                            <td>{{ $pending->name }}</td>
                            <td>{{ $pending->email }}</td>
                            <td>{{ optional($pending->branch)->name ?? 'No branch' }}</td>
                            <td>{{ $pending->created_at->format('M d, Y h:i A') }}</td>

                            <td>
                                <div class="actions-stack">
                                    <form method="POST" action="{{ route('admin.users.approve', $pending) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn">Accept</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.deny', $pending) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn secondary">Deny</button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $pending) }}"
                                          onsubmit="return confirm('Delete this signup request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn light">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No pending staff signup requests.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- CREATE USER --}}
        <div class="card bottom-space">
            <h2>Create Account</h2>

            <form method="POST" action="{{ route('admin.users.store') }}" class="create-user-form">
                @csrf

                <div class="field">
                    <label>Name</label>
                    <input name="name" required>
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

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

                <div class="field field-span-2">
                    <label>Branch</label>
                    <select name="branch_id">
                        <option value="">No branch / admin</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-actions field-span-2">
                    <button class="btn" type="submit">Create User</button>
                </div>
            </form>
        </div>

        {{-- USERS --}}
        <div class="card users-card">
            <h2>Users</h2>

            <div class="table-scroll">
                <table class="clean-table users-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="user-block">
                                        <strong>{{ $user->name }}</strong>
                                        <small>{{ $user->email }}</small>

                                        <form id="update-user-{{ $user->id }}"
                                              method="POST"
                                              action="{{ route('admin.users.update', $user) }}">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="name" value="{{ $user->name }}">
                                            <input type="hidden" name="email" value="{{ $user->email }}">

                                            <input type="password"
                                                   name="password"
                                                   placeholder="New password optional">
                                        </form>
                                    </div>
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
                                            <option value="{{ $branch->id }}"
                                                @selected($user->branch_id === $branch->id)>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <label class="checkbox-row">
                                        <input form="update-user-{{ $user->id }}"
                                               type="checkbox"
                                               name="is_active"
                                               value="1"
                                               @checked($user->is_active)>
                                        <span>Active</span>
                                    </label>
                                </td>

                                <td>
                                    <div class="actions-stack">
                                        <button class="btn light"
                                                form="update-user-{{ $user->id }}"
                                                type="submit">
                                            Save
                                        </button>

                                        @if($user->id !== auth()->id())
                                            @if($user->role === 'staff')
                                                <form method="POST"
                                                      action="{{ route('admin.users.reset-password', $user) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button class="btn light">
                                                        Reset
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST"
                                                  action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Delete this user?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn secondary">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-layouts.app>

<style>
    .page-stack {
        display: grid;
        gap: 24px;
    }

    .create-user-form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 20px;
        align-items: start;
    }

    .field-span-2 {
        grid-column: 1 / -1;
    }

    .form-actions {
        display: flex;
        justify-content: flex-start;
    }

    .table-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .clean-table.users-table {
        width: 100%;
        min-width: 850px;
        table-layout: auto;
    }

    .clean-table.users-table,
    .clean-table.users-table th,
    .clean-table.users-table td {
        font-size: 14px;
    }

    .clean-table.users-table th,
    .clean-table.users-table td {
        padding: 12px 14px;
        vertical-align: top;
    }

    .clean-table.users-table input,
    .clean-table.users-table select,
    .clean-table.users-table button {
        font-size: 14px;
    }

    .clean-table.users-table small {
        font-size: 12px;
    }

    .clean-table.users-table td:first-child {
        min-width: 240px;
    }

    .clean-table.users-table td:nth-child(2),
    .clean-table.users-table td:nth-child(3) {
        min-width: 140px;
    }

    .clean-table.users-table td:nth-child(5) {
        min-width: 180px;
    }

    .clean-table.users-table select,
    .clean-table.users-table input[type="password"] {
        width: 100%;
        min-width: 130px;
    }

    .user-block {
        display: grid;
        gap: 6px;
    }

    .actions-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    .actions-stack form {
        margin: 0;
    }

    .checkbox-row {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .create-user-form {
            grid-template-columns: 1fr;
        }

        .field-span-2 {
            grid-column: auto;
        }
    }
</style>