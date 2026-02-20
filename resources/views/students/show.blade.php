@extends('layouts.admin')

@section('title', 'Student Profile')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Student Profile</h3>
        <div class="flex space-x-2">
            <a href="{{ route('students.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
            <a href="{{ route('students.edit', $student) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Edit</a>
        </div>
    </div>

    <!-- Student Header Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
        <div class="px-6 py-4 flex items-center">
             <div class="flex-shrink-0 h-20 w-20">
                @if($student->photo)
                    <img class="h-20 w-20 rounded-full object-cover border-4 border-gray-200" src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                @else
                    <span class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-3xl">
                        {{ substr($student->name, 0, 1) }}
                    </span>
                @endif
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $student->name }}</h2>
                <div class="flex items-center mt-1 text-gray-600">
                    <span class="mr-4">Roll Number: {{ $student->roll_number }}</span>
                    <span class="mr-4">|</span>
                    <span>Status: {{ ucfirst(str_replace('_', ' ', $student->status)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Personal & Academic Info (Main) -->
        <div class="col-span-1 md:col-span-2 space-y-6">
            <!-- Academic Info -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50 text-gray-700">
                    Academic Information
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">Branch</span>
                        <div class="font-medium text-gray-900">{{ $student->branch->name ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Course</span>
                        <div class="font-medium text-gray-900">{{ $student->course->name ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Batch</span>
                        <div class="font-medium text-gray-900">{{ $student->batch->name ?? 'Not Assigned' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Registration Date</span>
                        <div class="font-medium text-gray-900">{{ $student->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50 text-gray-700">
                    Personal Information
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">Email</span>
                        <div class="font-medium text-gray-900">{{ $student->email ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Mobile</span>
                        <div class="font-medium text-gray-900">{{ $student->mobile ?? '-' }}</div>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <span class="text-gray-500 text-sm font-bold uppercase tracking-wider">Current Address</span>
                        <div class="mt-2 bg-gray-50 p-4 rounded-xl border border-gray-100 text-gray-700">
                             <div class="mb-2 pb-2 border-b border-gray-200">
                                 <span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">Address Line</span>
                                 <div class="font-medium">{{ $student->current_address ?? '-' }}</div>
                             </div>
                             <div class="grid grid-cols-3 gap-4">
                                 <div><span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">City</span><div class="font-medium">{{ $student->current_city ?? '-' }}</div></div>
                                 <div><span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">State</span><div class="font-medium">{{ $student->current_state ?? '-' }}</div></div>
                                 <div><span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">Pincode</span><div class="font-medium">{{ $student->current_pincode ?? '-' }}</div></div>
                             </div>
                        </div>
                    </div>
                    <div class="col-span-1 md:col-span-2 mt-4">
                        <span class="text-gray-500 text-sm font-bold uppercase tracking-wider">Permanent Address</span>
                        <div class="mt-2 bg-gray-50 p-4 rounded-xl border border-gray-100 text-gray-700">
                             <div class="mb-2 pb-2 border-b border-gray-200">
                                 <span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">Address Line</span>
                                 <div class="font-medium">{{ $student->permanent_address ?? '-' }}</div>
                             </div>
                             <div class="grid grid-cols-3 gap-4">
                                 <div><span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">City</span><div class="font-medium">{{ $student->permanent_city ?? '-' }}</div></div>
                                 <div><span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">State</span><div class="font-medium">{{ $student->permanent_state ?? '-' }}</div></div>
                                 <div><span class="text-[10px] text-gray-400 block uppercase font-black tracking-widest mb-1">Pincode</span><div class="font-medium">{{ $student->permanent_pincode ?? '-' }}</div></div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Parent Info -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50 text-gray-700">
                    Parent / Guardian Details
                </div>
                 <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 text-sm">{{ $student->parent_type }} Name</span>
                        <div class="font-medium text-gray-900">{{ $student->parent_name ?? '-' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500 text-sm">Parent Mobile</span>
                        <div class="font-medium text-gray-900">{{ $student->parent_mobile ?? '-' }}</div>
                    </div>
                     <div>
                        <span class="text-gray-500 text-sm">Parent Email</span>
                        <div class="font-medium text-gray-900">{{ $student->parent_email ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Fee & Stats -->
        <div class="col-span-1 space-y-6">
            <!-- Fee Summary -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-100">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50 flex items-center justify-between">
                    <span>Fee Summary</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                        {{ $student->after_placement_fee ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $student->after_placement_fee ? 'Placement Model' : 'Standard Model' }}
                    </span>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    @php
                        $trainingFee    = $student->training_fee ?? 0;
                        $placementFee   = $student->after_placement_amount ?? 0;
                        $discount       = $student->discount ?? 0;
                        $totalContract  = $student->total_contract;
                        $payableNow     = $student->payable_now;
                        $paid           = $student->paid_amount;
                        $balance        = $student->balance;
                        $credit         = $student->credit;
                    @endphp

                    {{-- Training Fee --}}
                    <div class="flex justify-between">
                        <span class="text-gray-600">Training Fee:</span>
                        <span class="font-semibold text-gray-900">₹{{ number_format($trainingFee, 2) }}</span>
                    </div>

                    {{-- Placement Fee (only for placement model) --}}
                    @if($student->after_placement_fee)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Placement Fee:
                            @if($student->status !== 'placed')
                                <span class="text-xs text-amber-600 font-bold">(locked)</span>
                            @endif
                        </span>
                        <span class="font-semibold {{ $student->status === 'placed' ? 'text-green-700' : 'text-gray-400' }}">
                            ₹{{ number_format($placementFee, 2) }}
                        </span>
                    </div>
                    @endif

                    {{-- Discount --}}
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount:</span>
                        <span class="font-semibold">-₹{{ number_format($discount, 2) }}</span>
                    </div>
                    @endif

                    {{-- Total Contract --}}
                    <div class="flex justify-between border-t pt-2 mt-1">
                        <span class="text-gray-600 font-medium">Total Fee:</span>
                        <span class="font-semibold text-gray-900">₹{{ number_format($totalContract, 2) }}</span>
                    </div>

                    {{-- Payable Now (phase-based highlight) --}}
                    <div class="flex justify-between bg-indigo-50 rounded px-2 py-1.5">
                        <span class="text-indigo-700 font-bold">Payable Now:</span>
                        <span class="font-bold text-indigo-700">₹{{ number_format($payableNow, 2) }}</span>
                    </div>

                    {{-- Paid --}}
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paid:</span>
                        <span class="font-semibold text-green-600">₹{{ number_format($paid, 2) }}</span>
                    </div>

                    {{-- Balance --}}
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600 font-bold">Balance:</span>
                        <span class="font-bold {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                            ₹{{ number_format($balance, 2) }}
                        </span>
                    </div>

                    {{-- Credit (only show if overpaid) --}}
                    @if($credit > 0)
                    <div class="flex justify-between bg-green-50 rounded px-2 py-1.5">
                        <span class="text-green-700 font-bold">Credit / Advance:</span>
                        <span class="font-bold text-green-700">₹{{ number_format($credit, 2) }}</span>
                    </div>
                    @endif

                    <div class="mt-3">
                        @if($student->after_placement_fee && $student->status !== 'placed')
                            <div class="bg-amber-50 border border-amber-200 text-amber-700 text-xs px-3 py-2 rounded-lg">
                                ⏳ Placement fee of ₹{{ number_format($placementFee, 2) }} becomes payable once status changes to <strong>Placed</strong>.
                            </div>
                        @elseif($student->after_placement_fee && $student->status === 'placed')
                            <div class="bg-green-50 border border-green-200 text-green-700 text-xs px-3 py-2 rounded-lg">
                                ✅ Student is placed. Full fee (Training + Placement) is now payable.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Payments (Stub) -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Recent Payments
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($student->payments()->latest()->take(3)->get() as $payment)
                        <li class="px-4 py-3 text-sm">
                            <div class="flex justify-between">
                                <span class="font-medium">₹{{ number_format($payment->amount) }}</span>
                                <span class="text-gray-500">{{ $payment->payment_date->format('d M') }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-3 text-sm text-gray-500 text-center">No recent payments.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-4">
                     <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 text-red-700 hover:bg-red-200 py-2 rounded font-semibold text-sm">
                            Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
