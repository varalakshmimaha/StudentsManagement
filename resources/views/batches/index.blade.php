@extends('layouts.admin')

@section('title', 'Batches')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Batches</h3>
        <a href="{{ route('batches.create') }}" class="px-6 py-3 bg-red-600 rounded-md text-white font-medium tracking-wide hover:bg-red-500">Add Batch</a>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-8">
        <!-- Filters -->
        <form method="GET" action="{{ route('batches.index') }}" class="mb-4 bg-white p-6 rounded-lg shadow-md border border-gray-300">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Batch name..." class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Branch -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Branch</label>
                    <select name="branch" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Course -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Course</label>
                    <select name="course" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Start Date From -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date From</label>
                    <input type="date" name="start_date_from" value="{{ request('start_date_from') }}" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Start Date To -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date To</label>
                    <input type="date" name="start_date_to" value="{{ request('start_date_to') }}" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2 md:col-span-2 lg:col-span-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none transition-colors flex-1">
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'branch', 'course', 'status', 'start_date_from', 'start_date_to']))
                        <a href="{{ route('batches.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded focus:outline-none transition-colors text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-indigo-50/50 border-b border-indigo-100">
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Batch Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Branch</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Total Fee</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Teacher(s)</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Students</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-black text-black uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($batches as $batch)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $batch->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $batch->branch->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $batch->course->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900 font-semibold">₹{{ number_format($batch->total_fee, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-500">
                                        @if($batch->teachers->count() > 0)
                                            {{ $batch->teachers->pluck('name')->join(', ') }}
                                        @else
                                            <span class="text-gray-400">Not assigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    @if($batch->status === 'upcoming')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Upcoming</span>
                                    @elseif($batch->status === 'ongoing')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Ongoing</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Completed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        {{ $batch->students->count() }}{{ $batch->capacity ? '/' . $batch->capacity : '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('batches.show', $batch) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    <a href="{{ route('batches.edit', $batch) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('batches.destroy', $batch) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this batch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                    No batches found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $batches->links() }}
        </div>
    </div>
</div>
@endsection
