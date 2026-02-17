@extends('layouts.admin')

@section('title', 'Courses')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Courses</h3>
        <a href="{{ route('courses.create') }}" class="px-6 py-3 bg-red-600 rounded-md text-white font-medium tracking-wide hover:bg-red-500">Add Course</a>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-8">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
            <form method="GET" action="{{ route('courses.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-grow">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Search Course</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..." class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="w-full md:w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors">Filter</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('courses.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 font-bold transition-colors">Clear</a>
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
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Default Total Fee</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($courses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $course->name }}</div>
                                    <div class="text-xs leading-5 text-gray-500">{{ $course->code ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $course->duration_value }} {{ Str::title($course->duration_unit) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900 font-semibold">₹{{ number_format($course->default_total_fee, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    @if($course->status === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $course->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    <a href="{{ route('courses.edit', $course) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    @if($course->status == 'active')
                                        <form action="{{ route('courses.update', $course) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to disable this course?');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $course->name }}">
                                            <input type="hidden" name="code" value="{{ $course->code }}">
                                            <input type="hidden" name="duration_value" value="{{ $course->duration_value }}">
                                            <input type="hidden" name="duration_unit" value="{{ $course->duration_unit }}">
                                            <input type="hidden" name="default_total_fee" value="{{ $course->default_total_fee }}">
                                            <input type="hidden" name="description" value="{{ $course->description }}">
                                            <input type="hidden" name="status" value="inactive">
                                            <button type="submit" class="text-orange-600 hover:text-orange-900">Disable</button>
                                        </form>
                                    @else
                                        <form action="{{ route('courses.update', $course) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $course->name }}">
                                            <input type="hidden" name="code" value="{{ $course->code }}">
                                            <input type="hidden" name="duration_value" value="{{ $course->duration_value }}">
                                            <input type="hidden" name="duration_unit" value="{{ $course->duration_unit }}">
                                            <input type="hidden" name="default_total_fee" value="{{ $course->default_total_fee }}">
                                            <input type="hidden" name="description" value="{{ $course->description }}">
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="text-green-600 hover:text-green-900">Enable</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                    No courses found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
