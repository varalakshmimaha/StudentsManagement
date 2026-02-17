@extends('layouts.admin')

@section('title', 'Due Report')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Due Report</h3>
        <div class="flex gap-2">
            <a href="{{ route('reports.export-due-report', request()->all()) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back to Reports</a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports.due-report') }}" class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                <select name="branch" class="form-select rounded-md shadow-sm w-full">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
                <select name="batch" class="form-select rounded-md shadow-sm w-full">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ request('batch') == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Student Status</label>
                <select name="status" class="form-select rounded-md shadow-sm w-full">
                    <option value="">All Status</option>
                    <option value="admission_done" {{ request('status') == 'admission_done' ? 'selected' : '' }}>Admission Done</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">Apply Filters</button>
            @if(request()->hasAny(['branch', 'batch', 'status']))
                <a href="{{ route('reports.due-report') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Clear</a>
            @endif
        </div>
    </form>

    <!-- Summary Card -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Total Outstanding</p>
                <p class="text-4xl font-bold mt-2">₹{{ number_format($totalDue, 2) }}</p>
                <p class="text-sm mt-2 opacity-90">{{ $students->total() }} students with pending payments</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Due Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Roll No</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Due Amount</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium text-gray-900">
                        {{ $student->roll_number }}
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200 text-sm">
                        <div class="font-medium text-gray-900">{{ $student->name }}</div>
                        <div class="text-xs text-gray-500">{{ $student->email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                        {{ $student->batch->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                        {{ $student->branch->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-bold text-red-600">
                        ₹{{ number_format($student->due_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-900">
                        {{ $student->mobile }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm">
                        @if($student->status == 'admission_done')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Admission Done</span>
                        @elseif($student->status == 'ongoing')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Ongoing</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Placed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No students with pending dues found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
@endsection
