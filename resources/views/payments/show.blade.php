@extends('layouts.admin')

@section('title', 'Payment Receipt')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center no-print">
        <h3 class="text-gray-700 text-3xl font-medium">Payment Receipt</h3>
        <div class="flex space-x-2">
            <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Print Receipt</button>
        </div>
    </div>

    <!-- Receipt Card -->
    <div class="mt-8 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 max-w-2xl mx-auto p-8" id="receipt">
         <div class="text-center border-b border-gray-200 pb-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Skill Technology</h1>
            <p class="text-gray-600">Student Management System</p>
            <p class="text-gray-500 text-sm mt-1">123 Education Lane, Learning City</p>
        </div>

        <div class="flex justify-between mb-8">
            <div>
                <p class="text-gray-500 text-sm uppercase tracking-wide">Billed To</p>
                <h4 class="text-lg font-bold text-gray-800">{{ $payment->student->name }}</h4>
                <p class="text-gray-600">{{ $payment->student->roll_number ?? 'No ID #' }}</p>
                <p class="text-gray-600 text-sm">{{ $payment->student->mobile }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 text-sm uppercase tracking-wide">Receipt Info</p>
                <h4 class="text-lg font-bold text-gray-800">{{ $payment->receipt_no }}</h4>
                <p class="text-gray-600">Date: {{ $payment->payment_date->format('d M Y') }}</p>
                <p class="text-gray-600 text-sm">Mode: {{ $payment->payment_mode }}</p>
            </div>
        </div>

        <div class="border-t border-b border-gray-200 py-4 mb-8">
            <div class="flex justify-between items-center font-bold text-gray-700">
                <span class="text-lg">Amount Paid</span>
                <span class="text-2xl">${{ number_format($payment->amount, 2) }}</span>
            </div>
             @if($payment->transaction_ref)
            <div class="flex justify-between items-center text-gray-500 text-sm mt-2">
                <span>Reference / Cheque No</span>
                <span>{{ $payment->transaction_ref }}</span>
            </div>
            @endif
        </div>
        
        <div class="mb-8">
            <p class="text-gray-500 text-sm uppercase tracking-wide mb-2">Remarks</p>
            <p class="text-gray-700 italic">{{ $payment->remarks ?? 'No remarks.' }}</p>
        </div>

        <div class="border-t border-gray-200 pt-8 flex justify-between items-end">
            <div class="text-sm text-gray-500">
                <p>Collected By: {{ $payment->collectedBy->name ?? 'System' }}</p>
                <p class="mt-1">Thank you for your payment!</p>
            </div>
            <div class="text-center">
                <div class="h-16 w-32 border-b border-gray-400 mb-2"></div>
                <p class="text-sm text-gray-600">Authorized Signature</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none;
        }
        body {
            background-color: white;
        }
        #receipt {
            box-shadow: none;
            border: none;
            margin: 0;
            max-width: 100%;
        }
    }
</style>
@endsection
