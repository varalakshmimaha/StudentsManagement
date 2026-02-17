@extends('layouts.admin')

@section('title', 'Fee Collection Report')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Fee Collection Report</h3>
        <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back to Reports</a>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('reports.fee-collection') }}" class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input rounded-md shadow-sm w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input rounded-md shadow-sm w-full">
            </div>
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
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-500">Apply Filters</button>
            @if(request()->hasAny(['date_from', 'date_to', 'branch', 'batch']))
                <a href="{{ route('reports.fee-collection') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Clear</a>
            @endif
        </div>
    </form>

    <!-- Summary Card -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Total Collection</p>
                <p class="text-4xl font-bold mt-2">₹{{ number_format($total, 2) }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Receipt No</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Collected By</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium text-gray-900">
                        {{ $payment->receipt_number }}
                    </td>
                    <td class="px-6 py-4 border-b border-gray-200 text-sm">
                        <div class="font-medium text-gray-900">{{ $payment->student->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->student->roll_number ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                        {{ $payment->student->batch->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-semibold text-green-600">
                        ₹{{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($payment->payment_mode) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm text-gray-500">
                        {{ $payment->collectedBy->name ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No payments found for the selected filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
