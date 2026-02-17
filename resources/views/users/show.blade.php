@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">User Details</h3>
        <div class="flex space-x-2">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
            <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    User Information
                </div>
                <div class="p-4">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0 h-20 w-20">
                            <div class="h-20 w-20 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-3xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="ml-6">
                            <h4 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-gray-500">{{ $user->role->name ?? 'No Role' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 text-sm">Mobile Number</span>
                            <div class="font-medium">{{ $user->mobile }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Email</span>
                            <div class="font-medium">{{ $user->email ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Username</span>
                            <div class="font-medium">{{ $user->username ?? '-' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Status</span>
                            <div>
                                @if($user->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Last Login</span>
                            <div class="font-medium">{{ $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'Never' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Joined Date</span>
                            <div class="font-medium">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Branches -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Assigned Branches
                </div>
                <div class="p-4">
                    @if($user->branches->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($user->branches as $branch)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $branch->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $branch->code ?? '-' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No branches assigned</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Quick Actions
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('users.edit', $user) }}" class="w-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200 py-2 rounded font-semibold text-sm text-center block">
                        Edit User
                    </a>
                    
                    @if($user->id != auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 text-red-700 hover:bg-red-200 py-2 rounded font-semibold text-sm">
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Role Info -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Role & Permissions
                </div>
                <div class="p-4">
                    <div class="text-center">
                        <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $user->role->name ?? 'No Role' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
