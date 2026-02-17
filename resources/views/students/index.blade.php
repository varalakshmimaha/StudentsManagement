@extends('layouts.admin')

@section('title', 'Students')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Students</h3>
        <a href="{{ route('students.create') }}" class="px-6 py-3 bg-red-600 rounded-md text-white font-medium tracking-wide hover:bg-red-500">Add Student</a>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-8">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
            <form method="GET" action="{{ route('students.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
                <div class="flex-grow">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Search Students</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, mobile, email..." class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="w-full lg:w-40">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Branch</label>
                    <select name="branch" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-40">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Course</label>
                    <select name="course" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-40">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Batch</label>
                    <select name="batch" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ request('batch') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-32">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold">Filter</button>
                    @if(request()->hasAny(['search', 'branch', 'course', 'batch', 'status']))
                        <a href="{{ route('students.index') }}" class="flex-1 lg:flex-none px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 font-bold text-center">Clear</a>
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
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Course / Batch</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($student->photo)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                            @else
                                                <span class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                                    {{ substr($student->name, 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $student->name }}</div>
                                            <div class="text-xs leading-5 text-gray-500">{{ $student->roll_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $student->mobile }}</div>
                                    <div class="text-xs leading-5 text-gray-500">{{ $student->email }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $student->course->code ?? $student->course->name ?? '-' }}</div>
                                    <div class="text-xs leading-5 text-gray-500">{{ $student->batch->name ?? 'No Batch' }}</div>
                                     <div class="text-xs leading-5 text-gray-400">{{ $student->branch->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900 font-medium">Due: ${{ number_format(($student->total_fee ?? 0) - ($student->paid_amount ?? 0)) }}</div>
                                    <div class="text-xs leading-5 text-gray-500">Total: ${{ number_format($student->total_fee ?? 0) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'inactive' => 'bg-red-100 text-red-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'dropped' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $statusColors[$student->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                    No students found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
