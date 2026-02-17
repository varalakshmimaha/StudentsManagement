@props(['lead', 'color' => 'gray'])

@php
    $borderColor = [
        'red' => 'border-red-500',
        'blue' => 'border-blue-500',
        'green' => 'border-green-500',
        'gray' => 'border-gray-300'
    ][$color] ?? 'border-gray-300';

    $bgIcon = [
        'red' => 'bg-red-100 text-red-600',
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'gray' => 'bg-gray-100 text-gray-600'
    ][$color] ?? 'bg-gray-100 text-gray-600';
@endphp

<div class="bg-white rounded-xl shadow-sm border-t-4 {{ $borderColor }} p-5 hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h5 class="font-bold text-gray-900 text-lg">{{ $lead->name }}</h5>
            <p class="text-xs text-gray-500 uppercase font-semibold">{{ $lead->status }} • {{ $lead->branch->name ?? 'No Branch' }}</p>
        </div>
        <div class="p-2 {{ $bgIcon }} rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        </div>
    </div>
    
    <div class="space-y-2 mb-4">
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            {{ $lead->email ?: 'No email' }}
        </div>
        <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Follow-up: <span class="ml-1 font-semibold {{ $color == 'red' ? 'text-red-600' : '' }}">{{ $lead->next_followup_date ? $lead->next_followup_date->format('d M Y') : 'N/A' }}</span>
        </div>
    </div>

    <div class="bg-gray-50 p-3 rounded-lg border mb-4">
        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Counsellor</p>
        <p class="text-sm font-medium text-gray-700">{{ $lead->counsellor->name ?? 'Not Assigned' }}</p>
    </div>

    <div class="flex items-center space-x-2">
        <a href="{{ route('leads.show', $lead) }}" class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 rounded text-center text-sm font-bold hover:bg-gray-50">Details</a>
        <a href="tel:{{ $lead->phone }}" class="flex-1 bg-indigo-600 text-white py-2 rounded text-center text-sm font-bold hover:bg-indigo-500">Call Now</a>
    </div>
</div>
