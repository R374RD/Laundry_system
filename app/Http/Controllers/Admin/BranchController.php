<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        return view('admin.branches.index', [
            'branches' => Branch::withCount(['users', 'orders'])->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:branches,name'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $branch = Branch::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created branch',
            'details' => $branch->name,
        ]);

        return back()->with('success', 'Branch created.');
    }

    public function update(Request $request, Branch $branch)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:branches,name,' . $branch->id],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $branch->update($data);

        return back()->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        if ($branch->users()->exists() || $branch->orders()->exists()) {
            return back()->withErrors([
                'branch' => 'This branch has users or orders and cannot be deleted.',
            ]);
        }

        $name = $branch->name;
        $branch->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted branch',
            'details' => $name,
        ]);

        return back()->with('success', 'Branch deleted.');
    }
}
