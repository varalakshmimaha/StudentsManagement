@extends('layouts.admin')

@section('title', 'Payments')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-medium">Payments</h3>
        <a href="{{ route('payments.create') }}" class="px-6 py-3 bg-red-600 rounded-md text-white font-medium tracking-wide hover:bg-red-500">Record Payment</a>
    </div>

    @if(session('success'))
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mt-8">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6">
            <form method="GET" action="{{ route('payments.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
                <div class="flex-grow">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Search Payment</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Receipt, Student..." class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="w-full lg:w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mode</label>
                    <select name="payment_mode" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Modes</option>
                        <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Online" {{ request('payment_mode') == 'Online' ? 'selected' : '' }}>Online</option>
                        <option value="Cheque" {{ request('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Bank Transfer" {{ request('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-white border border-gray-300 rounded-md shadow-sm w-full py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" class="flex-1 lg:flex-none px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-bold transition-colors">Filter</button>
                    @if(request()->hasAny(['search', 'payment_mode', 'date_from', 'date_to']))
                        <a href="{{ route('payments.index') }}" class="flex-1 lg:flex-none px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 font-bold text-center transition-colors">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Receipt No</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Collected By</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 font-medium text-indigo-600">
                                        <a href="{{ route('payments.show', $payment) }}">{{ $payment->receipt_no }}</a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">{{ $payment->payment_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $payment->student->name }}</div>
                                    <div class="text-xs leading-5 text-gray-500">{{ $payment->student->roll_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $payment->payment_mode }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $payment->collectedBy->name ?? 'System' }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                     <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this payment? This will revert the student balance.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                    No payments found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
