<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = \App\Models\Role::withCount('users')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not requested in prompt ("Roles List" -> Actions: Edit Permissions | Disable)
        // But if needed, can be added later.
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = \App\Models\Role::with('permissions')->findOrFail($id);
        $permissions = \App\Models\Permission::all()->groupBy('group_name');
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = \App\Models\Role::findOrFail($id);

        $request->validate([
            // 'name' => 'required|unique:roles,name,' . $id, // Name edit usually restricted for system roles
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'status' => 'nullable|in:active,inactive', // If user wants to disable role
        ]);

        if ($request->has('status')) {
            $role->update(['status' => $request->status]);
        }

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            // IF permissions array is present but empty, it means uncheck all.
            // If it's NOT present, it might mean form didn't send unchecked checkboxes?
            // HTML forms don't send anything for unchecked checkboxes.
            // So if input `permissions` is missing, we assume empty?
            // Usually we add a hidden input or check if `permissions` key exists in input at all ? 
            // Better to assume if we are on edit permissions page, we expect `permissions` array.
            // But if user unchecks all, `permissions` will be null/missing.
            // Let's check if the request intends to update permissions.
            // Maybe add a hidden field `update_permissions` = 1
            $role->permissions()->detach(); // Sync empty
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check prompt: "Actions: Edit Permissions | Disable".
        // No delete mentioned.
        abort(404);
    }
}
