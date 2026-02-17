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
                        <span class="text-gray-500 text-sm">Current Address</span>
                        <div class="font-medium text-gray-900">{{ $student->current_address ?? '-' }}</div>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <span class="text-gray-500 text-sm">Permanent Address</span>
                        <div class="font-medium text-gray-900">{{ $student->permanent_address ?? '-' }}</div>
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
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 font-semibold bg-gray-50">
                    Fee Summary
                </div>
                <div class="p-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Total Fee:</span>
                        <span class="font-bold">${{ number_format($student->total_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Paid:</span>
                        <span class="font-bold text-green-600">${{ number_format($student->paid_amount, 2) }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between">
                        <span class="text-gray-600">Balance Due:</span>
                        <span class="font-bold text-red-600">${{ number_format($student->total_fee - $student->paid_amount, 2) }}</span>
                    </div>
                    <div class="mt-4 text-center">
                         <span class="text-xs text-gray-500">Payment Mode: {{ $student->payment_mode ?? 'Not Set' }}</span>
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
                                <span class="font-medium">${{ number_format($payment->amount) }}</span>
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
