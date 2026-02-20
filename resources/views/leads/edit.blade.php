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

                <!-- Address Details -->
                <div class="col-span-1 md:col-span-2 bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                    <h4 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Address Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="current_address">Current Address</label>
                            <textarea name="current_address" id="current_address" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400 mb-3" placeholder="Address">{{ old('current_address', $lead->current_address) }}</textarea>
                            
                            <div class="grid grid-cols-3 gap-2">
                                    <input type="text" name="current_city" placeholder="City" value="{{ old('current_city', $lead->current_city) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                                    <input type="text" name="current_state" placeholder="State" value="{{ old('current_state', $lead->current_state) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                                    <input type="text" name="current_pincode" placeholder="Pin Code" value="{{ old('current_pincode', $lead->current_pincode) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="permanent_address">Permanent Address</label>
                            <textarea name="permanent_address" id="permanent_address" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400 mb-3" placeholder="Address">{{ old('permanent_address', $lead->permanent_address) }}</textarea>
                            
                            <div class="grid grid-cols-3 gap-2">
                                    <input type="text" name="permanent_city" placeholder="City" value="{{ old('permanent_city', $lead->permanent_city) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                                    <input type="text" name="permanent_state" placeholder="State" value="{{ old('permanent_state', $lead->permanent_state) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                                    <input type="text" name="permanent_pincode" placeholder="Pin Code" value="{{ old('permanent_pincode', $lead->permanent_pincode) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline border-gray-400">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Source -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="source">
                        Source
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('source') border-red-500 @enderror" id="source" name="source">
                        <option value="">Select Source</option>
                        <option value="Walk-in" {{ old('source', $lead->source) == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                        <option value="Referral" {{ old('source', $lead->source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                        <option value="Website" {{ old('source', $lead->source) == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Instagram" {{ old('source', $lead->source) == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="WhatsApp" {{ old('source', $lead->source) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="Call" {{ old('source', $lead->source) == 'Call' ? 'selected' : '' }}>Call</option>
                        <option value="Online Counseling" {{ old('source', $lead->source) == 'Online Counseling' ? 'selected' : '' }}>Online Counseling</option>
                    </select>
                    @error('source') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Interested Courses -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Interested Courses
                    </label>
                    <div class="relative" id="course-multi-select">
                        <div id="selected-box" class="shadow appearance-none border border-gray-400 rounded w-full py-1.5 px-3 text-gray-700 leading-tight focus-within:ring-2 focus-within:ring-blue-500 bg-white min-h-[42px] flex flex-wrap gap-2 items-center cursor-pointer justify-between" onclick="document.getElementById('course-dropdown').classList.toggle('hidden')">
                            <div id="selected-tags" class="flex flex-wrap gap-2">
                                <!-- Tags will be injected here -->
                            </div>
                            <span id="placeholder-text" class="text-gray-400 text-sm">Select courses...</span>
                            <div class="ml-auto">
                                <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        
                        <div id="course-dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-400 rounded shadow-2xl hidden max-h-60 overflow-y-auto">
                            @php
                                $options = ['Full Stack Web Development', 'Data Science & Machine Learning', 'Digital Marketing', 'Python Programming', 'Mobile App Development'];
                                $currentLeadCourses = old('interested_courses', $lead->interested_courses ?? []);
                            @endphp
                            @foreach($options as $course)
                                <div class="course-option px-4 py-2.5 hover:bg-blue-100 cursor-pointer text-sm flex items-center justify-between border-b last:border-0 border-gray-200" 
                                     onclick="toggleCourse('{{ $course }}')"
                                     data-course="{{ $course }}">
                                    <span class="font-medium text-gray-900">{{ $course }}</span>
                                    <svg class="check-icon h-5 w-5 text-blue-600 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Real hidden inputs for the form -->
                        <div id="hidden-course-inputs"></div>
                    </div>
                    @error('interested_courses') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
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
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->name }}" {{ old('status', $lead->status) == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                        @endforeach
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

<script>
    let selectedCourses = @json($currentLeadCourses);

    function toggleCourse(course) {
        const index = selectedCourses.indexOf(course);
        if (index > -1) {
            selectedCourses.splice(index, 1);
        } else {
            selectedCourses.push(course);
        }
        updateUI();
    }

    function removeCourse(course, event) {
        event.stopPropagation();
        toggleCourse(course);
    }

    function updateUI() {
        const tagsContainer = document.getElementById('selected-tags');
        const hiddenInputs = document.getElementById('hidden-course-inputs');
        const placeholder = document.getElementById('placeholder-text');
        
        tagsContainer.innerHTML = '';
        hiddenInputs.innerHTML = '';
        
        if (selectedCourses.length > 0) {
            placeholder.classList.add('hidden');
            selectedCourses.forEach(course => {
                // Add Tag
                const tag = document.createElement('span');
                tag.className = 'bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded flex items-center gap-1 border border-blue-200';
                tag.innerHTML = `${course} <button type="button" onclick="removeCourse('${course}', event)" class="hover:text-blue-900 font-bold">&times;</button>`;
                tagsContainer.appendChild(tag);
                
                // Add Hidden Input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'interested_courses[]';
                input.value = course;
                hiddenInputs.appendChild(input);
            });
        } else {
            placeholder.classList.remove('hidden');
        }
        
        // Update all icons in dropdown
        document.querySelectorAll('.course-option').forEach(opt => {
            const courseValue = opt.getAttribute('data-course');
            if (selectedCourses.includes(courseValue)) {
                opt.querySelector('.check-icon').classList.remove('hidden');
                opt.classList.add('bg-blue-50');
            } else {
                opt.querySelector('.check-icon').classList.add('hidden');
                opt.classList.remove('bg-blue-50');
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const select = document.getElementById('course-multi-select');
        const dropdown = document.getElementById('course-dropdown');
        if (!select.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Initialize UI
    window.onload = updateUI;
</script>
@endsection
