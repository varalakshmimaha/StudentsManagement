<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::with('role', 'branches');

        if ($request->has('role') && $request->role != '') {
            $query->where('role_id', $request->role);
        }

        if ($request->has('branch') && $request->branch != '') {
            $query->whereHas('branches', function ($q) use ($request) {
                $q->where('branches.id', $request->branch);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);
        
        $roles = \App\Models\Role::all();
        $branches = \App\Models\Branch::all();

        return view('users.index', compact('users', 'roles', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = \App\Models\Role::where('status', 'active')->get();
        $branches = \App\Models\Branch::where('status', 'active')->get();
        $permissions = \App\Models\Permission::all();
        return view('users.create', compact('roles', 'branches', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'role_id' => 'required|exists:roles,id',
            'branches' => 'array',
            'branches.*' => 'exists:branches,id',
            'status' => 'required|in:active,inactive',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = \App\Models\User::create($validated);

        if ($request->has('branches')) {
            $user->branches()->sync($request->branches);
        }

        if ($request->has('permissions')) {
            $user->permissions()->sync($request->permissions);
        }

        if ($request->has('save_and_new')) {
            return redirect()->route('users.create')->with('success', 'User created successfully.');
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = \App\Models\User::with('role', 'branches')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = \App\Models\User::with('branches', 'permissions')->findOrFail($id);
        $roles = \App\Models\Role::all(); // Show all roles even inactive ones for editing? Maybe restricted to active.
        $branches = \App\Models\Branch::all();
        $permissions = \App\Models\Permission::all();
        
        return view('users.edit', compact('user', 'roles', 'branches', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'username' => 'nullable|string|unique:users,username,' . $id,
            'role_id' => 'required|exists:roles,id',
            'branches' => 'array',
            'branches.*' => 'exists:branches,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|min:6', // Optional on update
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->has('branches')) {
            $user->branches()->sync($request->branches);
        } else {
             // If branches field is not present (e.g. unchecked all), sync empty?
             // Assuming form sends empty array or handled if hidden input present? 
             // Multiselect usually sends nothing if nothing selected.
             // We should check if intention was to update branches.
             $user->branches()->detach();
        }

        if ($request->has('permissions')) {
            $user->permissions()->sync($request->permissions);
        } else {
            $user->permissions()->detach();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
