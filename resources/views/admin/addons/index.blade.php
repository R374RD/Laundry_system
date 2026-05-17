<x-layouts.app heading="Add-On Services">
    <div class="grid grid-2">
        <div class="card">
            <h2>Add Service</h2>
            <form method="POST" action="{{ route('admin.addons.store') }}">
                @csrf
                <div class="field"><label>Name</label><input name="name" required></div>
                <div class="field"><label>Price</label><input type="number" step="0.01" min="0" name="price" required></div>
                <button class="btn" type="submit">Add Service</button>
            </form>
        </div>
        <div class="card">
            <h2>Services</h2>
            <table>
                <thead><tr><th>Name</th><th>Price</th><th>Status</th><th>Update</th></tr></thead>
                <tbody>
                    @foreach($addOns as $addOn)
                        <tr>
                            <form method="POST" action="{{ route('admin.addons.update', $addOn) }}">
                                @csrf
                                @method('PATCH')
                                <td><input name="name" value="{{ $addOn->name }}" required></td>
                                <td><input type="number" step="0.01" min="0" name="price" value="{{ $addOn->price }}" required></td>
                                <td>
                                    <label class="checkbox-row">
                                        <input type="checkbox" name="is_active" value="1" @checked($addOn->is_active)>
                                        <span>Active</span>
                                    </label>
                                </td>
                                <td><button class="btn light" type="submit">Save</button></td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
