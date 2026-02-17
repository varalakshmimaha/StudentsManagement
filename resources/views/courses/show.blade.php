@extends('layouts.admin')

@section('title', 'Course Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Course Details</h3>
        <div class="flex space-x-2">
            <a href="{{ route('courses.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
            <a href="{{ route('courses.edit', $course) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Course Info -->
        <div class="col-span-1 md:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Course Information
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">Course Name</span>
                        <div class="font-medium text-lg">{{ $course->name }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Course Code</span>
                        <div class="font-medium">{{ $course->code ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Duration</span>
                        <div class="font-medium">{{ $course->duration_value }} {{ Str::title($course->duration_unit) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Default Total Fee</span>
                        <div class="font-medium text-lg text-green-600">₹{{ number_format($course->default_total_fee, 2) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Status</span>
                        <div>
                            @if($course->status == 'active')
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
                        <div class="font-medium">{{ $course->created_at->format('d M Y') }}</div>
                    </div>
                    @if($course->description)
                        <div class="col-span-1 md:col-span-2">
                            <span class="text-gray-500 text-sm">Description</span>
                            <div class="font-medium">{{ $course->description }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Statistics
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $course->batches()->count() }}</div>
                        <div class="text-gray-500 text-sm">Total Batches</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $course->batches()->where('status', 'active')->count() }}</div>
                        <div class="text-gray-500 text-sm">Active Batches</div>
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
                    @if($course->status == 'active')
                        <form action="{{ route('courses.update', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to disable this course?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $course->name }}">
                            <input type="hidden" name="code" value="{{ $course->code }}">
                            <input type="hidden" name="duration_value" value="{{ $course->duration_value }}">
                            <input type="hidden" name="duration_unit" value="{{ $course->duration_unit }}">
                            <input type="hidden" name="default_total_fee" value="{{ $course->default_total_fee }}">
                            <input type="hidden" name="description" value="{{ $course->description }}">
                            <input type="hidden" name="status" value="inactive">
                            <button type="submit" class="w-full bg-orange-100 text-orange-700 hover:bg-orange-200 py-2 rounded font-semibold text-sm">
                                Disable Course
                            </button>
                        </form>
                    @else
                        <form action="{{ route('courses.update', $course) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $course->name }}">
                            <input type="hidden" name="code" value="{{ $course->code }}">
                            <input type="hidden" name="duration_value" value="{{ $course->duration_value }}">
                            <input type="hidden" name="duration_unit" value="{{ $course->duration_unit }}">
                            <input type="hidden" name="default_total_fee" value="{{ $course->default_total_fee }}">
                            <input type="hidden" name="description" value="{{ $course->description }}">
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="w-full bg-green-100 text-green-700 hover:bg-green-200 py-2 rounded font-semibold text-sm">
                                Enable Course
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 text-red-700 hover:bg-red-200 py-2 rounded font-semibold text-sm mt-2">
                            Delete Course
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
