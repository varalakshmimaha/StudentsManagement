@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Attendance Report</h3>
        <a href="{{ route('attendances.create') }}" class="px-6 py-3 bg-red-600 rounded-md text-white font-medium tracking-wide hover:bg-red-500">Mark Attendance</a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-8 border border-gray-200">
        <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select Batch</label>
                <select name="batch_id" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $selectedBatchId == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-gray-700 text-sm font-bold mb-2">Select Month</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" class="bg-white border border-gray-400 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors h-10">View Attendance</button>
        </form>
    </div>

    @if($selectedBatchId && count($students) > 0)
    <div class="bg-white shadow overflow-x-auto sm:rounded-lg">
        <div class="align-middle inline-block min-w-full">
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Student Name</th>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            <th class="px-1 py-2 border border-gray-200 bg-gray-50 text-center text-xs font-medium text-gray-500 w-8">{{ $i }}</th>
                        @endfor
                        <th class="px-4 py-2 border border-gray-200 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Present %</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($students as $student)
                        @php
                            $presentCount = 0;
                            $totalMarked = 0;
                        @endphp
                        <tr>
                            <td class="px-4 py-2 border border-gray-200 whitespace-no-wrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10">{{ $student->name }}</td>
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                    $status = $attendanceData[$student->id][$i]->status ?? '-';
                                    $colorClass = '';
                                    $shortStatus = '-';
                                    
                                    if ($status == 'present') {
                                        $shortStatus = 'P';
                                        $colorClass = 'text-green-600 font-bold';
                                        $presentCount++;
                                        $totalMarked++;
                                    } elseif ($status == 'absent') {
                                        $shortStatus = 'A';
                                        $colorClass = 'text-red-600 font-bold';
                                        $totalMarked++;
                                    } elseif ($status == 'late') {
                                        $shortStatus = 'L';
                                        $colorClass = 'text-yellow-600 font-bold';
                                        $presentCount += 0.5; // Maybe half day?
                                        $totalMarked++;
                                    } elseif ($status == 'excused') {
                                        $shortStatus = 'E';
                                        $colorClass = 'text-blue-600 font-bold';
                                        // $totalMarked++; // Depending on policy
                                    }
                                @endphp
                                <td class="px-1 py-2 border border-gray-200 text-center text-xs {{ $colorClass }}">
                                    {{ $shortStatus }}
                                </td>
                            @endfor
                            <td class="px-4 py-2 border border-gray-200 text-center text-sm">
                                @if($totalMarked > 0)
                                    {{ round(($presentCount / $totalMarked) * 100) }}%
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif($selectedBatchId)
    <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">No students found in this batch.</span>
    </div>
    @endif
</div>
@endsection
