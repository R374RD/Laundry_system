<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AddOnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddOnController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('admin.addons.index', [
            'addOns' => AddOnService::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        AddOnService::create($data + ['is_active' => true]);
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'Added add-on service', 'details' => $data['name']]);

        return back()->with('success', 'Add-on service added.');
    }

    public function update(Request $request, AddOnService $addon)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $addon->update($data + ['is_active' => false]);

        return back()->with('success', 'Add-on service updated.');
    }

    public function destroy(AddOnService $addOn)
{
    $addOn->delete();

    return back()->with('success', 'Service deleted successfully.');
}
}
