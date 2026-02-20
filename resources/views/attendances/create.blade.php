@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Mark Attendance</h3>
    
    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Selector Form -->
    <div class="bg-white shadow rounded-lg p-6 mt-8 mb-8 border border-gray-200">
        <form method="GET" action="{{ route('attendances.create') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label class="block text-gray-700 text-sm font-bold mb-2">Batch <span class="text-red-500">*</span></label>
                <select name="batch_id" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $selectedBatchId == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-gray-700 text-sm font-bold mb-2">Date <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ $selectedDate }}" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors h-10">Load Students</button>
        </form>
    </div>

    @if($selectedBatchId && count($students) > 0)
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="batch_id" value="{{ $selectedBatchId }}">
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h4 class="text-lg font-semibold text-gray-700">Student List</h4>
                <div class="text-sm text-gray-500">
                    Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                </div>
            </div>

            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($students as $student)
                        @php
                            $currentStatus = $existingAttendance[$student->id]->status ?? 'present'; // Default to present
                            $currentRemark = $existingAttendance[$student->id]->remarks ?? '';
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $student->name }}</div>
                                <div class="text-xs leading-5 text-gray-500">{{ $student->roll_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-400" name="attendance[{{ $student->id }}]" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                        <span class="ml-2 font-semibold text-green-700">Present</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-400" name="attendance[{{ $student->id }}]" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
                                        <span class="ml-2 font-semibold text-red-700">Absent</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" class="h-5 w-5 text-yellow-600 focus:ring-yellow-500 border-gray-400" name="attendance[{{ $student->id }}]" value="late" {{ $currentStatus == 'late' ? 'checked' : '' }}>
                                        <span class="ml-2 font-semibold text-yellow-700">Late</span>
                                    </label>
                                     <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-400" name="attendance[{{ $student->id }}]" value="excused" {{ $currentStatus == 'excused' ? 'checked' : '' }}>
                                        <span class="ml-2 font-semibold text-blue-700">Excused</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <input type="text" name="remarks[{{ $student->id }}]" value="{{ $currentRemark }}" class="shadow appearance-none border border-gray-400 rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                    Save Attendance
                </button>
            </div>
        </form>
    </div>
    @elseif($selectedBatchId)
     <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">No active students found in this batch.</span>
    </div>
    @endif
</div>
@endsection
