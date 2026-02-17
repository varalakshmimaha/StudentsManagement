@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Attendance Report</h3>
        <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back to Reports</a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
        <form method="GET" action="{{ route('reports.attendance-report') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-grow">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Select Month</label>
                <input type="month" name="month" value="{{ $month }}" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-full md:w-64">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Batch (Optional)</label>
                <select name="batch" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Batches</option>
                    @foreach($allBatches as $batch)
                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>{{ $batch->name }} - {{ $batch->course->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors h-10">Generate Report</button>
        </form>
    </div>

    <!-- Report Data -->
    @if($reportData->count() > 0)
        <div class="grid grid-cols-1 gap-6">
            @foreach($reportData as $data)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $data['batch']->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $data['batch']->course->name ?? '-' }} | {{ $data['batch']->branch->name ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold {{ $data['attendance_percentage'] >= 75 ? 'text-green-600' : ($data['attendance_percentage'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $data['attendance_percentage'] }}%
                                </div>
                                <div class="text-xs text-gray-500">Attendance</div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $data['total_students'] }}</div>
                                <div class="text-sm text-gray-500">Total Students</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $data['total_days'] }}</div>
                                <div class="text-sm text-gray-500">Total Days</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $data['present_count'] }}</div>
                                <div class="text-sm text-gray-500">Present Count</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ ($data['total_days'] * $data['total_students']) - $data['present_count'] }}</div>
                                <div class="text-sm text-gray-500">Absent Count</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="h-4 rounded-full {{ $data['attendance_percentage'] >= 75 ? 'bg-green-500' : ($data['attendance_percentage'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $data['attendance_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance data</h3>
            <p class="mt-1 text-sm text-gray-500">No attendance records found for the selected month and batch.</p>
        </div>
    @endif

    <!-- Legend -->
    <div class="mt-6 bg-white shadow-md rounded-lg p-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">Attendance Performance</h4>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Good (≥75%)</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Average (50-74%)</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-sm text-gray-600">Poor (<50%)</span>
            </div>
        </div>
    </div>
</div>
@endsection
