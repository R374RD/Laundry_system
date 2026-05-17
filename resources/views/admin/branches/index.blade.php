<x-layouts.app heading="Branches" subheading="Manage laundry branch records">
    <div class="grid grid-2">
        <div class="card">
            <h2>Add Branch</h2>
            <form method="POST" action="{{ route('admin.branches.store') }}">
                @csrf
                <div class="field">
                    <label>Branch Name</label>
                    <input name="name" value="{{ old('name') }}" required>
                </div>
                <div class="field">
                    <label>Address</label>
                    <input name="address" value="{{ old('address') }}">
                </div>
                <button class="btn" type="submit">Add Branch</button>
            </form>
        </div>

        <div class="card">
            <h2>Branch List</h2>
            <table>
                <thead><tr><th>Branch</th><th>Users</th><th>Orders</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr>
                            <td>
                                <form id="branch-{{ $branch->id }}" method="POST" action="{{ route('admin.branches.update', $branch) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input class="field-gap" name="name" value="{{ $branch->name }}" required>
                                    <input name="address" value="{{ $branch->address }}" placeholder="Address">
                                </form>
                            </td>
                            <td>{{ $branch->users_count }}</td>
                            <td>{{ $branch->orders_count }}</td>
                            <td class="actions">
                                <button class="btn light" form="branch-{{ $branch->id }}" type="submit">Save</button>
                                <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" onsubmit="return confirm('Delete this branch? Only empty branches can be deleted.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn secondary" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No branches yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
