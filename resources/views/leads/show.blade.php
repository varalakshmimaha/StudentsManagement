@extends('layouts.admin')

@section('title', 'Lead Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Lead Details: {{ $lead->name }}</h3>
        <div class="flex space-x-2">
            <a href="{{ route('leads.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
             @if(in_array($lead->status, ['interested', 'counselling_done']) && !$lead->student)
                <a href="{{ route('students.create', ['lead_id' => $lead->id]) }}" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-500 font-medium">Convert to Student</a>
            @elseif($lead->student)
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-md font-medium border border-green-200">Converted</span>
            @endif
        </div>
    </div>
    
    <div class="mt-6">
        <!-- Status Badge -->
        <span class="px-3 py-1 rounded-full text-sm font-semibold 
            @if($lead->status == 'new') bg-blue-100 text-blue-800
            @elseif($lead->status == 'contacted') bg-yellow-100 text-yellow-800
            @elseif($lead->status == 'interested') bg-green-100 text-green-800
            @elseif($lead->status == 'lost') bg-gray-100 text-gray-800
            @elseif($lead->status == 'converted') bg-teal-100 text-teal-800
            @else bg-gray-100 text-gray-800 @endif">
            Status: {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
        </span>
    </div>

    <!-- Tabs Header -->
    <div class="mt-8 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="openTab('info')" id="tab-info" class="tab-btn whitespace-no-wrap py-4 px-1 border-b-2 border-indigo-500 font-medium text-sm leading-5 text-indigo-600 focus:outline-none focus:text-indigo-800 focus:border-indigo-700" aria-current="page">
                Lead Info
            </button>
            <button onclick="openTab('counselling')" id="tab-counselling" class="tab-btn whitespace-no-wrap py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Counselling Notes
            </button>
            <button onclick="openTab('logs')" id="tab-logs" class="tab-btn whitespace-no-wrap py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300">
                Call Logs / Follow-ups
            </button>
        </nav>
    </div>

    <!-- Tab 1: Info -->
    <div id="content-info" class="tab-content mt-8">
        <form action="{{ route('leads.update', $lead->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PUT')
            <!-- Personal Info -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Personal Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                 <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lead Name</label>
                    <input type="text" name="name" value="{{ old('name', $lead->name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Source</label>
                     <select name="source" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                         @foreach(['Walk-in', 'Referral', 'Website', 'Instagram', 'WhatsApp', 'Call'] as $src)
                            <option value="{{ $src }}" {{ $lead->source == $src ? 'selected' : '' }}>{{ $src }}</option>
                         @endforeach
                     </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Preferred Branch</label>
                    <select name="preferred_branch_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">Select</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $lead->preferred_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Assigned Counsellor</label>
                    <select name="assigned_counsellor_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">Select</option>
                        @foreach($counsellors as $counsellor)
                            <option value="{{ $counsellor->id }}" {{ $lead->assigned_counsellor_id == $counsellor->id ? 'selected' : '' }}>{{ $counsellor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                     <select name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                         @foreach(['new', 'contacted', 'scheduled', 'counselling_done', 'interested', 'not_interested', 'lost', 'converted'] as $status)
                            <option value="{{ $status }}" {{ $lead->status == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                         @endforeach
                     </select>
                </div>
            </div>

            <!-- Address Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Address Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Current Address</label>
                    <textarea name="current_address" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ old('current_address', $lead->current_address) }}</textarea>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Permanent Address</label>
                    <textarea name="permanent_address" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ old('permanent_address', $lead->permanent_address) }}</textarea>
                </div>
            </div>

            <!-- Academic Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Academic Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Highest Qualification</label>
                    <input type="text" name="highest_qualification" value="{{ old('highest_qualification', $lead->highest_qualification) }}" placeholder="e.g. B.Tech" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">College Name</label>
                    <input type="text" name="college_name" value="{{ old('college_name', $lead->college_name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Percentage / CGPA</label>
                    <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $lead->percentage) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
            </div>

            <!-- Parent Details -->
            <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Parent / Guardian Details</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Relation Type</label>
                    <select name="parent_type" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">Select</option>
                        <option value="Father" {{ $lead->parent_type == 'Father' ? 'selected' : '' }}>Father</option>
                        <option value="Mother" {{ $lead->parent_type == 'Mother' ? 'selected' : '' }}>Mother</option>
                        <option value="Guardian" {{ $lead->parent_type == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Parent Name</label>
                    <input type="text" name="parent_name" value="{{ old('parent_name', $lead->parent_name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                 <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Parent Mobile</label>
                    <input type="text" name="parent_mobile" value="{{ old('parent_mobile', $lead->parent_mobile) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                 <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Parent Email</label>
                    <input type="email" name="parent_email" value="{{ old('parent_email', $lead->parent_email) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">Save Info</button>
            </div>
        </form>
    </div>

    <!-- Tab 2: Counselling Notes -->
    <div id="content-counselling" class="tab-content mt-8 hidden">
        <form action="{{ route('leads.update', $lead->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Counselling Date</label>
                    <input type="date" name="counselling_date" value="{{ old('counselling_date', $lead->counselling_date ? $lead->counselling_date->format('Y-m-d') : '') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Outcome</label>
                    <select name="counselling_outcome" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">Select Outcome</option>
                        <option value="Interested" {{ $lead->counselling_outcome == 'Interested' ? 'selected' : '' }}>Interested</option>
                        <option value="Not Interested" {{ $lead->counselling_outcome == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                        <option value="Need Time" {{ $lead->counselling_outcome == 'Need Time' ? 'selected' : '' }}>Need Time</option>
                        <option value="Will Join Later" {{ $lead->counselling_outcome == 'Will Join Later' ? 'selected' : '' }}>Will Join Later</option>
                    </select>
                </div>
                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Estimated Joining Date</label>
                     <input type="date" name="estimated_joining_date" value="{{ old('estimated_joining_date', $lead->estimated_joining_date ? $lead->estimated_joining_date->format('Y-m-d') : '') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 md:w-1/2">
                </div>
                 <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Counselling Notes</label>
                    <textarea name="counselling_notes" rows="5" class="shadow border rounded w-full py-2 px-3 text-gray-700">{{ old('counselling_notes', $lead->counselling_notes) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-500">Save Notes & Update Status</button>
            </div>
        </form>
    </div>

    <!-- Tab 3: Call Logs -->
    <div id="content-logs" class="tab-content mt-8 hidden">
        <!-- Add Log Form -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
            <h4 class="font-bold text-gray-700 mb-4">Add Follow-up / Call Log</h4>
            <form action="{{ route('lead_followups.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                
                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1">Outcome</label>
                    <select name="outcome" class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700">
                         <option value="Not reachable">Not reachable</option>
                         <option value="Call back later">Call back later</option>
                         <option value="Interested">Interested</option>
                         <option value="Not Interested">Not Interested</option>
                         <option value="Visited">Visited</option>
                    </select>
                </div>
                <div>
                     <label class="block text-gray-700 text-xs font-bold mb-1">Next Follow-up Date</label>
                     <input type="date" name="next_followup_date" class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700">
                </div>
                 <div class="md:col-span-2">
                     <label class="block text-gray-700 text-xs font-bold mb-1">Notes</label>
                     <input type="text" name="notes" placeholder="Call summary..." class="shadow border rounded w-full py-2 px-3 text-sm text-gray-700">
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 text-sm font-bold">Add Log</button>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                         <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Outcome</th>
                          <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                           <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Next Follow-up</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lead->followups->sortByDesc('created_at') as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500">
                            {{ $log->created_at->format('d M Y, h:i A') }}
                        </td>
                         <td class="px-6 py-4 whitespace-no-wrap text-sm font-medium text-gray-900">
                            {{ $log->outcome }}
                        </td>
                         <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $log->notes }}
                        </td>
                         <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500">
                            {{ $log->next_followup_date ? $log->next_followup_date->format('d M Y') : '-' }}
                        </td>
                         <td class="px-6 py-4 whitespace-no-wrap text-sm text-gray-500">
                            {{ $log->createdBy->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function openTab(tabName) {
        // Hide all
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-indigo-500', 'text-indigo-600');
            el.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected
        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-' + tabName).classList.add('border-indigo-500', 'text-indigo-600');
    }
</script>
@endsection
