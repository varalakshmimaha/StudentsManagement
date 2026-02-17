@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Users</h3>
        <a href="{{ route('users.create') }}" class="px-6 py-3 bg-red-600 rounded-md text-white font-medium tracking-wide hover:bg-red-500">Add User</a>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-8">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
                <div class="flex-grow">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Search Users</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, mobile, email..." class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="w-full lg:w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                    <select name="role" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Branch</label>
                    <select name="branch" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full lg:w-40">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors">Filter</button>
                    @if(request()->hasAny(['search', 'role', 'branch', 'status']))
                        <a href="{{ route('users.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 font-bold transition-colors">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Branches</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs leading-5 text-gray-500">{{ $user->username ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $user->mobile }}</div>
                                    <div class="text-xs leading-5 text-gray-500">{{ $user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $user->role->name ?? 'No Role' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-500">
                                        @if($user->branches->count() > 0)
                                            {{ $user->branches->pluck('name')->join(', ') }}
                                        @else
                                            <span class="text-gray-400">No branches</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    @if($user->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    @if($user->id != auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
