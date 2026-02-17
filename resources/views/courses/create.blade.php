@extends('layouts.admin')

@section('title', 'Add New Course')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Add New Course</h3>
    
    <div class="mt-8">
        <form action="{{ route('courses.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Course Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Web Development">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="code">
                        Course Code
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror" id="code" type="text" name="code" value="{{ old('code') }}" placeholder="WEB-DEV-001">
                    @error('code')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div class="mb-4 flex space-x-2">
                    <div class="w-1/2">
                         <label class="block text-gray-700 text-sm font-bold mb-2" for="duration_value">
                            Duration Value <span class="text-red-500">*</span>
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_value') border-red-500 @enderror" id="duration_value" type="number" name="duration_value" value="{{ old('duration_value') }}" placeholder="6">
                         @error('duration_value')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="duration_unit">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_unit') border-red-500 @enderror" id="duration_unit" name="duration_unit">
                            <option value="weeks" {{ old('duration_unit') == 'weeks' ? 'selected' : '' }}>Weeks</option>
                            <option value="months" {{ old('duration_unit') == 'months' ? 'selected' : '' }}>Months</option>
                            <option value="years" {{ old('duration_unit') == 'years' ? 'selected' : '' }}>Years</option>
                            <option value="days" {{ old('duration_unit') == 'days' ? 'selected' : '' }}>Days</option>
                        </select>
                         @error('duration_unit')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Default Fee -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="default_total_fee">
                        Default Course Fee <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('default_total_fee') border-red-500 @enderror" id="default_total_fee" type="number" step="0.01" name="default_total_fee" value="{{ old('default_total_fee') }}" placeholder="15000">
                    @error('default_total_fee')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" id="description" name="description" rows="3" placeholder="Course details...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status <span class="text-red-500">*</span>
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('courses.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <div class="flex space-x-4">
                    <button type="submit" name="save_and_new" value="1" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save & Add Another
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Save Course
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
