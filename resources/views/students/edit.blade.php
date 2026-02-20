@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Student</h3>
    
    <div class="mt-8">
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" autocomplete="off">
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
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" id="status" name="status" onchange="calculateFinalFee()">
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

            <!-- Current Address -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <div class="flex items-center gap-2 mb-4 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Current Address</span>
                </div>
                
                <div class="mb-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="current_address">
                        Address Line
                    </label>
                    <textarea class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('current_address') border-red-500 @enderror" id="current_address" name="current_address" rows="2" placeholder="House No, Street, Landmark...">{{ old('current_address', $student->current_address) }}</textarea>
                    @error('current_address') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="current_city">
                            City
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('current_city') border-red-500 @enderror" id="current_city" type="text" name="current_city" value="{{ old('current_city', $student->current_city) }}" placeholder="e.g. Bangalore">
                        @error('current_city') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="current_state">
                            State
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('current_state') border-red-500 @enderror" id="current_state" type="text" name="current_state" value="{{ old('current_state', $student->current_state) }}" placeholder="e.g. Karnataka">
                        @error('current_state') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="current_pincode">
                            Pincode
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('current_pincode') border-red-500 @enderror" id="current_pincode" type="text" name="current_pincode" value="{{ old('current_pincode', $student->current_pincode) }}" placeholder="560001">
                        @error('current_pincode') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Permanent Address -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">Permanent Address</span>
                    </div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" id="same_as_current" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 transition" onclick="copyAddress()">
                        <span class="ml-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Same as Current</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="permanent_address">
                        Address Line
                    </label>
                    <textarea class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('permanent_address') border-red-500 @enderror" id="permanent_address" name="permanent_address" rows="2" placeholder="House No, Street, Landmark...">{{ old('permanent_address', $student->permanent_address) }}</textarea>
                    @error('permanent_address') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="permanent_city">
                            City
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('permanent_city') border-red-500 @enderror" id="permanent_city" type="text" name="permanent_city" value="{{ old('permanent_city', $student->permanent_city) }}" placeholder="e.g. Bangalore">
                        @error('permanent_city') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="permanent_state">
                            State
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('permanent_state') border-red-500 @enderror" id="permanent_state" type="text" name="permanent_state" value="{{ old('permanent_state', $student->permanent_state) }}" placeholder="e.g. Karnataka">
                        @error('permanent_state') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="permanent_pincode">
                            Pincode
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-300 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('permanent_pincode') border-red-500 @enderror" id="permanent_pincode" type="text" name="permanent_pincode" value="{{ old('permanent_pincode', $student->permanent_pincode) }}" placeholder="560001">
                        @error('permanent_pincode') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Academic Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Academic Details</h4>
            <!-- Academic Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Academic Details</h4>
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Branch -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="branch_id">
                            Branch <span class="text-red-500">*</span>
                        </label>
                        <select class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('branch_id') border-red-500 @enderror" id="branch_id" name="branch_id">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $student->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Course -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="course_id">
                            Course <span class="text-red-500">*</span>
                        </label>
                        <select onchange="filterBatches()" class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('course_id') border-red-500 @enderror" id="course_id" name="course_id">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" data-fee="{{ $course->total_fee ?? 0 }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Batch -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="batch_id">
                            Batch <span class="text-red-500">*</span>
                        </label>
                        <select class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('batch_id') border-red-500 @enderror" id="batch_id" name="batch_id">
                            <option value="">Select Batch</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" data-course-id="{{ $batch->course_id }}" {{ old('batch_id', $student->batch_id) == $batch->id ? 'selected' : '' }}>{{ $batch->name }} ({{ $batch->status }})</option>
                            @endforeach
                        </select>
                        @error('batch_id') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Highest Qualification -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="highest_qualification">
                            Highest Qualification
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('highest_qualification') border-red-500 @enderror" id="highest_qualification" type="text" name="highest_qualification" value="{{ old('highest_qualification', $student->highest_qualification) }}" placeholder="B.Tech, MCA...">
                        @error('highest_qualification') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- College Name -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="college_name">
                             College Name
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('college_name') border-red-500 @enderror" id="college_name" type="text" name="college_name" value="{{ old('college_name', $student->college_name) }}" placeholder="University Name">
                        @error('college_name') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Percentage -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="percentage">
                            Percentage / CGPA
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('percentage') border-red-500 @enderror" id="percentage" type="number" step="0.01" name="percentage" value="{{ old('percentage', $student->percentage) }}" placeholder="85.5">
                        @error('percentage') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Fee Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Fee Details</h4>
            
            <!-- Fee Status Display -->
             <div class="mb-6 bg-indigo-50 p-6 rounded-2xl border border-indigo-100 shadow-sm flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-indigo-600 p-3 rounded-xl mr-4 shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Fee Status</p>
                        <p class="text-xl font-black capitalize 
                            @if($student->fee_status == 'fully_paid') text-green-600 
                            @elseif($student->fee_status == 'partial') text-yellow-600 
                            @else text-red-600 @endif">
                            {{ str_replace('_', ' ', $student->fee_status) }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Total Paid Contributions</p>
                    <p class="text-2xl font-black text-indigo-700">
                        ₹{{ number_format(\App\Models\Payment::where('student_id', $student->id)->sum('amount'), 2) }}
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
                <!-- Fee Mode Toggle (Branded Radio-like buttons) -->
                <div class="flex gap-4 mb-6">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="after_placement_fee" value="0" {{ old('after_placement_fee', $student->after_placement_fee) == 0 ? 'checked' : '' }} class="hidden peer" onchange="toggleFeeMode(false)">
                        <div class="px-6 py-3 rounded-xl border-2 border-gray-100 bg-white text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50 group-hover:border-indigo-200">
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-indigo-600">Standard Model</span>
                            <span class="block text-sm font-bold text-gray-700">Training Fee Only</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="after_placement_fee" value="1" {{ old('after_placement_fee', $student->after_placement_fee) == 1 ? 'checked' : '' }} class="hidden peer" onchange="toggleFeeMode(true)">
                        <div class="px-6 py-3 rounded-xl border-2 border-gray-100 bg-white text-center transition-all peer-checked:border-indigo-600 peer-checked:bg-indigo-50 group-hover:border-indigo-200">
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest peer-checked:text-indigo-600">Placement Model</span>
                            <span class="block text-sm font-bold text-gray-700">After Placement Fee</span>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6 items-end">
                    <!-- Training Fee / Total Fee -->
                    <div id="training_fee_container">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="training_fee" id="fee_label">
                            Training Fee <span class="text-red-500">*</span>
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('training_fee') border-red-500 @enderror" id="training_fee" type="number" step="0.01" name="training_fee" value="{{ old('training_fee', $student->training_fee) }}" oninput="calculateFinalFee()">
                        <input type="hidden" name="total_fee" id="total_fee_hidden" value="{{ $student->total_fee }}">
                        @error('training_fee') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- After Placement Amount (Visible only in Placement Model) -->
                    <div id="placement_amount_container" style="display: none;">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="after_placement_amount">
                            After Placement Fee <span class="text-red-500">*</span>
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('after_placement_amount') border-red-500 @enderror" id="after_placement_amount" type="number" step="0.01" name="after_placement_amount" value="{{ old('after_placement_amount', $student->after_placement_amount) }}" oninput="calculateFinalFee()">
                        @error('after_placement_amount') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="discount">
                            Discount
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('discount') border-red-500 @enderror" id="discount" type="number" step="0.01" name="discount" value="{{ old('discount', $student->discount) }}" oninput="calculateFinalFee()">
                        @error('discount') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Final Fee (Payable Now) -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="final_fee">
                            Final Fee (Payable) <span class="text-red-500">*</span>
                        </label>
                        <input class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-800 bg-gray-100 leading-tight focus:outline-none font-bold" id="final_fee" type="number" step="0.01" name="final_fee" value="{{ old('final_fee', $student->final_fee) }}" readonly>
                        @error('final_fee') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Type -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1" for="payment_type">
                            Payment Type <span class="text-red-500">*</span>
                        </label>
                        <select class="shadow-sm appearance-none border border-gray-200 rounded-xl w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_type') border-red-500 @enderror" id="payment_type" name="payment_type">
                            <option value="full" {{ old('payment_type', $student->payment_type) == 'full' ? 'selected' : '' }}>Full Payment</option>
                            <option value="installments" {{ old('payment_type', $student->payment_type) == 'installments' ? 'selected' : '' }}>Installments</option>
                        </select>
                        @error('payment_type') <p class="text-red-500 text-[10px] italic mt-1">{{ $message }}</p> @enderror
                    </div>
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

    function toggleFeeMode(isPlacement) {
        const placementContainer = document.getElementById('placement_amount_container');
        const placementInput = document.getElementById('after_placement_amount');

        if (isPlacement) {
            // Show Placement Fee; Training Fee ALWAYS stays visible
            placementContainer.style.display = 'block';
        } else {
            // Hide and reset Placement Fee ONLY; never touch training fee
            placementContainer.style.display = 'none';
            placementInput.value = 0;
        }
        calculateFinalFee();
    }

    function calculateFinalFee() {
        const isPlacementInput = document.querySelector('input[name="after_placement_fee"]:checked');
        const isPlacement = isPlacementInput ? isPlacementInput.value == '1' : false;
        const status = document.getElementById('status').value;

        const trainingFee = parseFloat(document.getElementById('training_fee').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const placementAmount = isPlacement ? (parseFloat(document.getElementById('after_placement_amount').value) || 0) : 0;

        // Total Fee = Training + Placement (always, regardless of status)
        const totalFee = trainingFee + placementAmount;
        document.getElementById('total_fee_hidden').value = totalFee.toFixed(2);

        // Payable Now (final_fee stored in DB):
        // Training + Placement only if placed; else training only
        let payable = trainingFee - discount;
        if (isPlacement && status === 'placed') {
            payable += placementAmount;
        }
        if (payable < 0) payable = 0;

        document.getElementById('final_fee').value = payable.toFixed(2);
    }
    
    function copyAddress() {
        const fields = ['address', 'city', 'state', 'pincode'];
        const isChecked = document.getElementById('same_as_current').checked;
        
        fields.forEach(field => {
            const currentVal = document.getElementById('current_' + field).value;
            const permField = document.getElementById('permanent_' + field);
            if (isChecked) {
                permField.value = currentVal;
            } else {
                permField.value = '';
            }
        });
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', function() {
        filterBatches();
        const isPlacementInput = document.querySelector('input[name="after_placement_fee"]:checked');
        if (isPlacementInput) {
            toggleFeeMode(isPlacementInput.value == '1');
        }
        calculateFinalFee();
    });
</script>
@endsection
