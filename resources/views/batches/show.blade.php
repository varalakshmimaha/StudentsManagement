@extends('layouts.admin')

@section('title', 'Batch Details')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Batch Details</h3>
        <div class="flex space-x-2">
            <a href="{{ route('batches.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
            <a href="{{ route('batches.edit', $batch) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Edit</a>
        </div>
    </div>

    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $batch->name }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
                Batch Information
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Branch
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $batch->branch->name ?? '-' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Course
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $batch->course->name ?? '-' }} ({{ $batch->course->code ?? '-' }})
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Status
                    </dt>
                    <dd class="mt-1 text-sm leading-5 sm:mt-0 sm:col-span-2">
                        @php
                            $statusColors = [
                                'upcoming' => 'bg-yellow-100 text-yellow-800',
                                'ongoing' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-800',
                            ];
                            $color = $statusColors[$batch->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($batch->status) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                     <dt class="text-sm leading-5 font-medium text-gray-500">
                        Date Range
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : 'TBD' }} 
                        to 
                        {{ $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('d M Y') : 'TBD' }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Capacity
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $batch->students->count() }} / {{ $batch->capacity }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Total Fee
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        ${{ number_format($batch->total_fee, 2) }}
                    </dd>
                </div>
                 <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        Assigned Teachers
                    </dt>
                    <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        @forelse($batch->teachers as $teacher)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $teacher->name }}
                            </span>
                        @empty
                            -
                        @endforelse
                    </dd>
                </div>
            </dl>
        </div>
        
        <!-- Fee Summary -->
         <div class="px-4 py-5 sm:px-6 mt-6 border-t font-semibold">
            Fee Summary
        </div>
        <div class="border-t border-gray-200">
             <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                <div class="bg-green-50 p-4 rounded border border-green-200">
                    <dt class="text-sm font-medium text-green-500 truncate">Total Expected</dt>
                    <dd class="mt-1 text-2xl font-semibold text-green-900">{{ number_format($totalExpected, 2) }}</dd>
                </div>
                <div class="bg-blue-50 p-4 rounded border border-blue-200">
                    <dt class="text-sm font-medium text-blue-500 truncate">Total Collected</dt>
                    <dd class="mt-1 text-2xl font-semibold text-blue-900">{{ number_format($totalCollected, 2) }}</dd>
                </div>
                <div class="bg-red-50 p-4 rounded border border-red-200">
                    <dt class="text-sm font-medium text-red-500 truncate">Total Due</dt>
                    <dd class="mt-1 text-2xl font-semibold text-red-900">{{ number_format($totalDue, 2) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Students List (Accessory) -->
        <div class="px-4 py-5 sm:px-6 mt-6 border-t flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Enrolled Students</h3>
            <a href="{{ route('batches.export', $batch) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 text-sm">Export CSV</a>
        </div>
        <div class="bg-white border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                @forelse($batch->students as $student)
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                         <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                            {{ $student->name }} <span class="text-gray-500 text-xs">({{ $student->roll_number }})</span>
                        </div>
                        <div class="ml-2 flex-shrink-0 flex space-x-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($student->fee_status == 'fully_paid') bg-green-100 text-green-800 
                                @elseif($student->fee_status == 'partial') bg-yellow-100 text-yellow-800 
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $student->fee_status)) }}
                            </span>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                    </div>
                </li>
                @empty
                 <li class="px-4 py-4 sm:px-6 text-sm text-gray-500 text-center">No students enrolled yet.</li>
                @endforelse
            </ul>
        </div>
        
        <div class="px-4 py-5 bg-white border-t border-gray-200 flex justify-end">
             <form action="{{ route('batches.destroy', $batch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this batch?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete Batch</button>
            </form>
        </div>
    </div>
</div>
@endsection
