@extends('layouts.admin')

@section('title', 'Branch Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Branch Details</h3>
        <div class="flex space-x-2">
            <a href="{{ route('branches.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
            <a href="{{ route('branches.edit', $branch) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Branch Info -->
        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Branch Information
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">Branch Name</span>
                        <div class="font-medium text-lg">{{ $branch->name }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Branch Code</span>
                        <div class="font-medium">{{ $branch->code ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Status</span>
                        <div>
                            @if($branch->status == 'active')
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
                        <span class="text-gray-500 text-sm">Created Date</span>
                        <div class="font-medium">{{ $branch->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <span class="text-gray-500 text-sm">Address</span>
                        <div class="font-medium">{{ $branch->address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Statistics
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $branch->students()->count() }}</div>
                        <div class="text-gray-500 text-sm">Total Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $branch->batches()->count() }}</div>
                        <div class="text-gray-500 text-sm">Total Batches</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $branch->students()->where('status', 'active')->count() }}</div>
                        <div class="text-gray-500 text-sm">Active Students</div>
                    </div>
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
                    @if($branch->status == 'active')
                        <form action="{{ route('branches.update', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to disable this branch?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $branch->name }}">
                            <input type="hidden" name="code" value="{{ $branch->code }}">
                            <input type="hidden" name="address" value="{{ $branch->address }}">
                            <input type="hidden" name="status" value="inactive">
                            <button type="submit" class="w-full bg-orange-100 text-orange-700 hover:bg-orange-200 py-2 rounded font-semibold text-sm">
                                Disable Branch
                            </button>
                        </form>
                    @else
                        <form action="{{ route('branches.update', $branch) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $branch->name }}">
                            <input type="hidden" name="code" value="{{ $branch->code }}">
                            <input type="hidden" name="address" value="{{ $branch->address }}">
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="w-full bg-green-100 text-green-700 hover:bg-green-200 py-2 rounded font-semibold text-sm">
                                Enable Branch
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch? This will affect all related data.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 text-red-700 hover:bg-red-200 py-2 rounded font-semibold text-sm mt-2">
                            Delete Branch
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
