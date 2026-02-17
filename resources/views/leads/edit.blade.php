@extends('layouts.admin')

@section('title', 'Edit Lead')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Lead: {{ $lead->name }}</h3>
    
    <div class="mt-8">
        <form action="{{ route('leads.update', $lead->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Lead Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name', $lead->name) }}" placeholder="John Doe">
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile">
                        Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('mobile') border-red-500 @enderror" id="mobile" type="text" name="mobile" value="{{ old('mobile', $lead->mobile) }}" placeholder="1234567890">
                    @error('mobile') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                 <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" id="email" type="email" name="email" value="{{ old('email', $lead->email) }}" placeholder="john@example.com">
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Source -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="source">
                        Source
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('source') border-red-500 @enderror" id="source" type="text" name="source" value="{{ old('source', $lead->source) }}" placeholder="e.g. Facebook, Walk-in">
                    @error('source') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Course -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">
                        Interested Course
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_id') border-red-500 @enderror" id="course_id" name="course_id">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $lead->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                 <!-- Assigned To -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="assigned_to">
                        Assign To
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('assigned_to') border-red-500 @enderror" id="assigned_to" name="assigned_to">
                        <option value="">Select Staff</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                        <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="interested" {{ old('status', $lead->status) == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('leads.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Lead
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
