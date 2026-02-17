@extends('layouts.admin')

@section('title', 'Add New Lead')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Add New Lead</h3>
    
    <div class="mt-8">
        <form action="{{ route('leads.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Lead Name <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe">
                    @error('name') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('phone') border-red-500 @enderror" id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="1234567890">
                    @error('phone') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com">
                    @error('email') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Source -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="source">
                        Source
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="source" name="source">
                        <option value="">Select Source</option>
                        <option value="Walk-in" {{ old('source') == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                        <option value="Referral" {{ old('source') == 'Referral' ? 'selected' : '' }}>Referral</option>
                        <option value="Website" {{ old('source') == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Instagram" {{ old('source') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="WhatsApp" {{ old('source') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Call" {{ old('source') == 'Call' ? 'selected' : '' }}>Call</option>
                    </select>
                     @error('source') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Branch -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="preferred_branch_id">
                        Preferred Branch
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('preferred_branch_id') border-red-500 @enderror" id="preferred_branch_id" name="preferred_branch_id">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('preferred_branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('preferred_branch_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Assigned To -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="assigned_counsellor_id">
                        Assigned Counsellor
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('assigned_counsellor_id') border-red-500 @enderror" id="assigned_counsellor_id" name="assigned_counsellor_id">
                        <option value="">Select Counsellor</option>
                        @foreach($counsellors as $counsellor)
                            <option value="{{ $counsellor->id }}" {{ old('assigned_counsellor_id') == $counsellor->id ? 'selected' : '' }}>{{ $counsellor->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_counsellor_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Interested Courses -->
                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="interested_courses">
                        Interested Courses (Hold Ctrl/Cmd to select multiple)
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="interested_courses" name="interested_courses[]" multiple size="4">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ (collect(old('interested_courses'))->contains($course->id)) ? 'selected' : '' }}>{{ $course->name }}</option>
                        @endforeach
                    </select>
                     @error('interested_courses') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                
                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                     <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline border-gray-400" id="status" name="status">
                         <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                         <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                         <option value="walk_in" {{ old('status') == 'walk_in' ? 'selected' : '' }}>Walk-in</option>
                         <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Address Details -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                <h4 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Address Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="current_address">Current Address</label>
                        <textarea name="current_address" id="current_address" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">{{ old('current_address') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="permanent_address">Permanent Address</label>
                        <textarea name="permanent_address" id="permanent_address" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">{{ old('permanent_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Academic Details -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                <h4 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Academic Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="highest_qualification">Highest Qualification</label>
                        <input type="text" name="highest_qualification" id="highest_qualification" value="{{ old('highest_qualification') }}" placeholder="e.g. B.Tech" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="college_name">College Name</label>
                        <input type="text" name="college_name" id="college_name" value="{{ old('college_name') }}" placeholder="College Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="percentage">Percentage / CGPA</label>
                        <input type="number" step="0.01" name="percentage" id="percentage" value="{{ old('percentage') }}" placeholder="0.00" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                    </div>
                </div>
            </div>

            <!-- Parent Details -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                <h4 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Parent / Guardian Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_type">Relation Type</label>
                        <select name="parent_type" id="parent_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                            <option value="">Select</option>
                            <option value="Father" {{ old('parent_type') == 'Father' ? 'selected' : '' }}>Father</option>
                            <option value="Mother" {{ old('parent_type') == 'Mother' ? 'selected' : '' }}>Mother</option>
                            <option value="Guardian" {{ old('parent_type') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_name">Parent Name</label>
                        <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_mobile">Parent Mobile</label>
                        <input type="text" name="parent_mobile" id="parent_mobile" value="{{ old('parent_mobile') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_email">Parent Email</label>
                        <input type="email" name="parent_email" id="parent_email" value="{{ old('parent_email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('leads.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Lead
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
