@extends('layouts.admin')

@section('title', 'Leads Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Leads</h3>
        
        <div class="flex items-center gap-4 mt-4 md:mt-0">
             <!-- View Switcher -->
            <div class="bg-gray-100 p-1 rounded-lg flex shadow-inner">
                <a href="{{ route('leads.index') }}" class="px-4 py-2 text-sm font-medium bg-white text-blue-600 shadow rounded-md">
                    List View
                </a>
                <a href="{{ route('leads.kanban') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 rounded-md transition-colors">
                    Kanban View
                </a>
            </div>

            <a href="{{ route('leads.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 font-medium tracking-wide shadow-sm">
                + Add Lead
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8 border border-gray-300">
        <form method="GET" action="{{ route('leads.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
            <!-- Search -->
            <div class="flex-grow">
                <label class="block text-gray-700 text-sm font-bold mb-2">Search Lead</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Phone or Email" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Status -->
            <div class="w-full lg:w-48">
                 <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                 <select name="status" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                     <option value="">All Statuses</option>
                     @foreach(['new', 'contacted', 'scheduled', 'counselling_done', 'interested', 'not_interested', 'lost', 'converted'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                     @endforeach
                 </select>
            </div>

            <!-- Branch -->
            <div class="w-full lg:w-48">
                <label class="block text-gray-700 text-sm font-bold mb-2">Branch</label>
                <select name="branch" class="bg-white border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
             
             <!-- Filter Button -->
            <div class="w-full lg:w-auto">
                <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors h-10">Apply Filters</button>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-white shadow overflow-x-auto sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Interested Courses</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Follow-up</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leads as $lead)
                <tr class="{{ $lead->next_followup_date && \Carbon\Carbon::parse($lead->next_followup_date)->isPast() && $lead->status != 'converted' ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-no-wrap">
                        <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                            <a href="{{ route('leads.show', $lead->id) }}" class="hover:underline">{{ $lead->name }}</a>
                        </div>
                        <div class="text-xs text-gray-500">{{ $lead->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        <div class="text-sm leading-5 text-gray-900">
                            <a href="tel:{{ $lead->phone }}" class="hover:text-blue-600">{{ $lead->phone }}</a>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        @php
                            $statusColors = [
                                'new' => 'bg-blue-100 text-blue-800',
                                'contacted' => 'bg-yellow-100 text-yellow-800',
                                'scheduled' => 'bg-purple-100 text-purple-800',
                                'counselling_done' => 'bg-orange-100 text-orange-800',
                                'interested' => 'bg-green-100 text-green-800',
                                'not_interested' => 'bg-red-100 text-red-800',
                                'lost' => 'bg-gray-100 text-gray-800',
                                'converted' => 'bg-teal-100 text-teal-800',
                            ];
                            $color = $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                        {{ $lead->counsellor->name ?? 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                        @if($lead->interested_courses && is_array($lead->interested_courses))
                            {{ implode(', ', $lead->interested_courses) }}
                        @else
                            -
                        @endif
                    </td>
                     <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                        @if($lead->next_followup_date)
                            <span class="{{ \Carbon\Carbon::parse($lead->next_followup_date)->isPast() ? 'text-red-600 font-bold' : '' }}">
                                {{ \Carbon\Carbon::parse($lead->next_followup_date)->format('d M') }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
                        <a href="{{ route('leads.show', $lead->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                        <!-- Add Call Modal Trigger here if needed, but 'View' is usually enough -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $leads->links() }}
    </div>
</div>
@endsection
