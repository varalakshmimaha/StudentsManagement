@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Student</h3>
    
    <div class="mt-8">
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')

            <!-- Personal Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Personal Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name', $student->name) }}" placeholder="John Doe">
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Roll Number -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="roll_number">
                        Roll Number <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('roll_number') border-red-500 @enderror" id="roll_number" type="text" name="roll_number" value="{{ old('roll_number', $student->roll_number) }}" placeholder="R-2026-001">
                    @error('roll_number') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" id="email" type="email" name="email" value="{{ old('email', $student->email) }}" placeholder="john@example.com">
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile">
                        Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('mobile') border-red-500 @enderror" id="mobile" type="text" name="mobile" value="{{ old('mobile', $student->mobile) }}" placeholder="1234567890">
                    @error('mobile') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Photo -->
                  <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                        Photo
                    </label>
                    <input class="w-full text-gray-700 px-3 py-2 border rounded" id="photo" type="file" name="photo">
                    @if($student->photo)
                        <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ asset('storage/' . $student->photo) }}" target="_blank" class="text-blue-500">View Photo</a></p>
                    @endif
                    @error('photo') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status">
                        <option value="admission_done" {{ old('status', $student->status) == 'admission_done' ? 'selected' : '' }}>Admission Done</option>
                        <option value="ongoing" {{ old('status', $student->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="placed" {{ old('status', $student->status) == 'placed' ? 'selected' : '' }}>Placed</option>
                        <option value="dropped" {{ old('status', $student->status) == 'dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Address Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Address Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Current Address -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="current_address">
                        Current Address
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('current_address') border-red-500 @enderror" id="current_address" name="current_address" rows="3">{{ old('current_address', $student->current_address) }}</textarea>
                    @error('current_address') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Permanent Address -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="permanent_address">
                        Permanent Address
                    </label>
                     <div class="flex items-center mb-2">
                         <input type="checkbox" id="same_as_current" class="mr-2" onchange="copyAddress()">
                         <span class="text-sm text-gray-600">Same as Current Address</span>
                    </div>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('permanent_address') border-red-500 @enderror" id="permanent_address" name="permanent_address" rows="3">{{ old('permanent_address', $student->permanent_address) }}</textarea>
                    @error('permanent_address') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Academic Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Academic Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Branch -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="branch_id">
                        Branch <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('branch_id') border-red-500 @enderror" id="branch_id" name="branch_id">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $student->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Course -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">
                        Course <span class="text-red-500">*</span>
                    </label>
                    <select onchange="filterBatches()" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('course_id') border-red-500 @enderror" id="course_id" name="course_id">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" data-fee="{{ $course->total_fee ?? 0 }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Batch -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="batch_id">
                        Batch <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('batch_id') border-red-500 @enderror" id="batch_id" name="batch_id">
                        <option value="">Select Batch</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" data-course-id="{{ $batch->course_id }}" {{ old('batch_id', $student->batch_id) == $batch->id ? 'selected' : '' }}>{{ $batch->name }} ({{ $batch->status }})</option>
                        @endforeach
                    </select>
                    @error('batch_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Highest Qualification -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="highest_qualification">
                        Highest Qualification
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('highest_qualification') border-red-500 @enderror" id="highest_qualification" type="text" name="highest_qualification" value="{{ old('highest_qualification', $student->highest_qualification) }}" placeholder="B.Tech, MCA...">
                    @error('highest_qualification') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- College Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="college_name">
                         College Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('college_name') border-red-500 @enderror" id="college_name" type="text" name="college_name" value="{{ old('college_name', $student->college_name) }}" placeholder="University Name">
                    @error('college_name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Percentage -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="percentage">
                        Percentage / CGPA
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('percentage') border-red-500 @enderror" id="percentage" type="number" step="0.01" name="percentage" value="{{ old('percentage', $student->percentage) }}" placeholder="85.5">
                    @error('percentage') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

             <!-- Fee Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Fee Details</h4>
            
            <!-- Fee Status Display -->
             <div class="mb-6 bg-blue-50 p-4 rounded border border-blue-200">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-gray-600">Current Fee Status:</p>
                        <p class="text-lg font-bold capitalize 
                            @if($student->fee_status == 'fully_paid') text-green-600 
                            @elseif($student->fee_status == 'partial') text-yellow-600 
                            @else text-red-600 @endif">
                            {{ str_replace('_', ' ', $student->fee_status) }}
                        </p>
                    </div>
                     <div>
                        <p class="text-sm font-bold text-gray-600">Total Paid:</p>
                        <p class="text-lg font-bold text-blue-600">
                             {{ number_format(\App\Models\Payment::where('student_id', $student->id)->sum('amount'), 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Fee -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="total_fee">
                        Total Course Fee <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('total_fee') border-red-500 @enderror" id="total_fee" type="number" step="0.01" name="total_fee" value="{{ old('total_fee', $student->total_fee) }}" oninput="calculateFinalFee()">
                    @error('total_fee') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Discount -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="discount">
                        Discount
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('discount') border-red-500 @enderror" id="discount" type="number" step="0.01" name="discount" value="{{ old('discount', $student->discount) }}" oninput="calculateFinalFee()">
                    @error('discount') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Final Fee -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="final_fee">
                        Final Fee <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline" id="final_fee" type="number" step="0.01" name="final_fee" value="{{ old('final_fee', $student->final_fee) }}" readonly>
                     @error('final_fee') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                 <!-- Payment Type -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_type">
                        Payment Type <span class="text-red-500">*</span>
                    </label>
                     <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('payment_type') border-red-500 @enderror" id="payment_type" name="payment_type">
                         <option value="full" {{ old('payment_type', $student->payment_type) == 'full' ? 'selected' : '' }}>Full Payment</option>
                         <option value="installments" {{ old('payment_type', $student->payment_type) == 'installments' ? 'selected' : '' }}>Installments</option>
                    </select>
                    @error('payment_type') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
            
             <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                    Notes
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="notes" name="notes" rows="2">{{ old('notes', $student->notes) }}</textarea>
            </div>

            <!-- Parent Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Parent / Guardian Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                 <!-- Parent Type -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_type">
                        Relation Type <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('parent_type') border-red-500 @enderror" id="parent_type" name="parent_type">
                        <option value="Father" {{ old('parent_type', $student->parent_type) == 'Father' ? 'selected' : '' }}>Father</option>
                        <option value="Mother" {{ old('parent_type', $student->parent_type) == 'Mother' ? 'selected' : '' }}>Mother</option>
                        <option value="Guardian" {{ old('parent_type', $student->parent_type) == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                    </select>
                    @error('parent_type') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                 <!-- Parent Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_name">
                        Parent Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('parent_name') border-red-500 @enderror" id="parent_name" type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}">
                    @error('parent_name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                 <!-- Parent Mobile -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_mobile">
                        Parent Mobile <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('parent_mobile') border-red-500 @enderror" id="parent_mobile" type="text" name="parent_mobile" value="{{ old('parent_mobile', $student->parent_mobile) }}">
                    @error('parent_mobile') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                <!-- Parent Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_email">
                        Parent Email (Optional)
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('parent_email') border-red-500 @enderror" id="parent_email" type="email" name="parent_email" value="{{ old('parent_email', $student->parent_email) }}">
                    @error('parent_email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Student
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterBatches() {
        var courseSelect = document.getElementById('course_id');
        var batchSelect = document.getElementById('batch_id');
        var selectedCourseId = courseSelect.value;
        
        var options = batchSelect.options;

        for (var i = 0; i < options.length; i++) {
            var option = options[i];
            var courseId = option.getAttribute('data-course-id');
            
            if (option.value === "") continue; 

            // In edit mode, if already selected, make sure valid
            if (selectedCourseId === "" || courseId === selectedCourseId) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
    }

    function calculateFinalFee() {
        var total = parseFloat(document.getElementById('total_fee').value) || 0;
        var discount = parseFloat(document.getElementById('discount').value) || 0;
        var final = total - discount;
        if(final < 0) final = 0;
        document.getElementById('final_fee').value = final.toFixed(2);
    }
    
    function copyAddress() {
        if(document.getElementById('same_as_current').checked) {
            document.getElementById('permanent_address').value = document.getElementById('current_address').value;
        } else {
             // In edit, we might not want to clear just on uncheck, but fine for now
             if(document.getElementById('permanent_address').value === document.getElementById('current_address').value) {
                 document.getElementById('permanent_address').value = '';
             }
        }
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', function() {
        filterBatches();
        calculateFinalFee();
    });
</script>
@endsection
