@extends('layouts.admin')

@section('title', 'Branches')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Branches</h3>
        <a href="{{ route('branches.create') }}" class="px-6 py-3 bg-blue-600 rounded-md text-white font-medium tracking-wide hover:bg-blue-500">Add Branch</a>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-8">
        <!-- Filters -->
        <form method="GET" action="{{ route('branches.index') }}" class="mb-4 flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..." class="form-input rounded-md shadow-sm w-full md:w-1/3">
            
            <select name="status" class="form-select rounded-md shadow-sm w-full md:w-1/6">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-500">Filter</button>
            @if(request()->has('search') || request()->has('status'))
                <a href="{{ route('branches.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Clear</a>
            @endif
        </form>

        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Branch Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Branch Code</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($branches as $branch)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $branch->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $branch->code ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-500">{{ Str::limit($branch->address, 50) ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    @if($branch->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $branch->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('branches.show', $branch) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    <a href="{{ route('branches.edit', $branch) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    @if($branch->status == 'active')
                                        <form action="{{ route('branches.update', $branch) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to disable this branch?');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $branch->name }}">
                                            <input type="hidden" name="code" value="{{ $branch->code }}">
                                            <input type="hidden" name="address" value="{{ $branch->address }}">
                                            <input type="hidden" name="status" value="inactive">
                                            <button type="submit" class="text-orange-600 hover:text-orange-900">Disable</button>
                                        </form>
                                    @else
                                        <form action="{{ route('branches.update', $branch) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $branch->name }}">
                                            <input type="hidden" name="code" value="{{ $branch->code }}">
                                            <input type="hidden" name="address" value="{{ $branch->address }}">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="text-green-600 hover:text-green-900">Enable</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                    No branches found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $branches->links() }}
        </div>
    </div>
</div>
@endsection
