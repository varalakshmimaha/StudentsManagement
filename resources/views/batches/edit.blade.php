@extends('layouts.admin')

@section('title', 'Edit Batch')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Batch: {{ $batch->name }}</h3>
    
    <div class="mt-8">
        <form action="{{ route('batches.update', $batch->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Batch Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Batch Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name', $batch->name) }}" placeholder="Batch 1">
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Branch -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="branch_id">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('branch_id') border-red-500 @enderror" id="branch_id" name="branch_id">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $batch->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Course -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">
                        Course <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_id') border-red-500 @enderror" id="course_id" name="course_id">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" data-fee="{{ $course->default_total_fee }}" {{ old('course_id', $batch->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Start Date -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror" id="start_date" type="date" name="start_date" value="{{ old('start_date', $batch->start_date) }}">
                    @error('start_date') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        End Date (Optional)
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror" id="end_date" type="date" name="end_date" value="{{ old('end_date', $batch->end_date) }}">
                    @error('end_date') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Total Fee -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="total_fee">
                        Total Fee <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('total_fee') border-red-500 @enderror" id="total_fee" type="number" step="0.01" name="total_fee" value="{{ old('total_fee', $batch->total_fee) }}">
                    @error('total_fee') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Batch Capacity -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="capacity">
                        Batch Capacity (Optional)
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('capacity') border-red-500 @enderror" id="capacity" type="number" name="capacity" value="{{ old('capacity', $batch->capacity) }}" placeholder="30">
                    @error('capacity') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                        <option value="upcoming" {{ old('status', $batch->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ old('status', $batch->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status', $batch->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Assign Teachers -->
                <div class="col-span-1 md:col-span-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Assign Teacher(s)
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @forelse($teachers as $teacher)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="teachers[]" value="{{ $teacher->id }}" class="form-checkbox h-5 w-5 text-red-600" {{ in_array($teacher->id, old('teachers', $batch->teachers->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">{{ $teacher->name }}</span>
                            </label>
                        @empty
                            <p class="text-gray-500 col-span-4">No teachers available.</p>
                        @endforelse
                    </div>
                    @error('teachers') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('batches.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Batch
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-populate fee from selected course
    document.getElementById('course_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const fee = selectedOption.getAttribute('data-fee');
        if (fee) {
            document.getElementById('total_fee').value = fee;
        }
    });
</script>
@endsection
