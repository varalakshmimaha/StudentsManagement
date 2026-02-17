@extends('layouts.admin')

@section('title', 'Edit Permissions')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Role: {{ $role->name }}</h3>
    
    <div class="mt-8">
        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            
            <!-- Hidden field to indicate permissions update intention -->
            <input type="hidden" name="permissions_update" value="1">

            <!-- Status -->
            <div class="mb-6 border-b pb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Role Status
                </label>
                <select class="shadow appearance-none border rounded w-full md:w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                    <option value="active" {{ old('status', $role->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $role->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <h4 class="text-lg font-semibold text-gray-800 mb-4">Permissions</h4>

            @foreach($permissions as $group => $perms)
            <div class="mb-6">
                <h5 class="text-md font-medium text-gray-700 border-b border-gray-200 pb-2 mb-3 uppercase tracking-wider">{{ $group }}</h5>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($perms as $permission)
                    <div class="flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                            {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                            class="form-checkbox h-5 w-5 text-red-600 rounded focus:ring-red-500 border-gray-300 transition duration-150 ease-in-out">
                        <label class="ml-2 text-gray-700 text-sm">
                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Permissions
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
