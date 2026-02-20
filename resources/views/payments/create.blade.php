@extends('layouts.admin')

@section('title', 'Record Payment')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border-color: #d1d5db !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Record Payment</h3>
    
    <div class="mt-8">
        <form action="{{ route('payments.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Student -->
                <div class="mb-4 col-span-1 md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">
                        Select Student <span class="text-red-500">*</span>
                    </label>
                    <select onchange="updateDue(this)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('student_id') border-red-500 @enderror select2" id="student_id" name="student_id">
                        <option value="">Search Student...</option>
                        @foreach($students as $student)
                            @php
                                $due = $student->total_fee - $student->paid_amount;
                            @endphp
                            <option value="{{ $student->id }}" data-due="{{ $due }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->roll_number }}) - Due: ₹{{ number_format($due, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-gray-500 text-xs mt-2" id="due_display">Select a student to see balance due.</p>
                    @error('student_id') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Payment Date -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_date">
                        Payment Date <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('payment_date') border-red-500 @enderror" id="payment_date" type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}">
                    @error('payment_date') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Amount -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                        Amount Paid <span class="text-red-500">*</span>
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror" id="amount" type="number" step="0.01" name="amount" value="{{ old('amount') }}" placeholder="0.00">
                     <p class="text-gray-500 text-xs mt-1"><a href="#" onclick="fillDue(); return false;" class="text-blue-500 font-semibold hover:underline">Pay Full Due</a></p>
                    @error('amount') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Payment Mode -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_mode">
                        Payment Mode <span class="text-red-500">*</span>
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('payment_mode') border-red-500 @enderror" id="payment_mode" name="payment_mode">
                        <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Online" {{ old('payment_mode') == 'Online' ? 'selected' : '' }}>Online (UPI/Card)</option>
                        <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                    @error('payment_mode') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Transaction Ref -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="transaction_ref">
                        Transaction Ref / Cheque No
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="transaction_ref" type="text" name="transaction_ref" value="{{ old('transaction_ref') }}" placeholder="Optional">
                </div>
                
                 <!-- Remarks -->
                <div class="col-span-1 md:col-span-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="remarks">
                        Remarks
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="remarks" name="remarks" rows="2" placeholder="Optional notes">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <a href="{{ route('payments.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Search for a student...",
            allowClear: true
        });

        $('#student_id').on('change', function() {
            updateDue(this);
        });

        if($('#student_id').val()) {
            updateDue(document.getElementById('student_id'));
        }
    });

    function updateDue(select) {
        var selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;
        
        var due = selectedOption.getAttribute('data-due');
        var display = document.getElementById('due_display');
        
        if(due !== null && select.value !== "") {
            display.innerHTML = 'Balance Due: <span class="font-bold text-red-600">₹' + parseFloat(due).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</span>';
        } else {
            display.innerText = 'Select a student to see balance due.';
        }
    }
    
    function fillDue() {
        var select = document.getElementById('student_id');
        var selectedOption = select.options[select.selectedIndex];
        var due = selectedOption.getAttribute('data-due');
        
        if(due !== null && select.value !== "") {
             document.getElementById('amount').value = parseFloat(due).toFixed(2);
        } else {
            alert('Please select a student first.');
        }
    }
</script>
@endpush
@endsection
