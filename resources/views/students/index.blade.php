@extends('layouts.admin')

@section('title', 'Students Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h3 class="text-gray-800 text-3xl font-extrabold tracking-tight">Student Directory</h3>
            <p class="text-gray-500 text-sm mt-1">Manage all your enrolled students and their academic progress.</p>
        </div>
        <a href="{{ route('students.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transform transition hover:-translate-y-1">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add New Student
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 animate-fade-in-down">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3 font-medium text-green-800">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Filters Section -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
        <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Search Students</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Mobile, Roll No..." class="pl-10 block w-full bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 text-gray-900 placeholder-gray-400 shadow-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Branch</label>
                <select name="branch" class="block w-full bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 text-gray-900 shadow-sm">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Course</label>
                <select name="course" class="block w-full bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 text-gray-900 shadow-sm">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="block w-full bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 text-gray-900 shadow-sm">
                    <option value="">All Status</option>
                    <option value="admission_done" {{ request('status') == 'admission_done' ? 'selected' : '' }}>Admission Done</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed</option>
                    <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all active:scale-95">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'branch', 'course', 'batch', 'status']))
                    <a href="{{ route('students.index') }}" class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 font-bold py-2.5 px-4 rounded-lg text-center transition-all shadow-sm active:scale-95">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden text-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-indigo-50/50 border-b border-indigo-100">
                        <th class="px-6 py-4 font-black text-black uppercase tracking-wider">Student Info</th>
                        <th class="px-6 py-4 font-black text-black uppercase tracking-wider">Academic Details</th>
                        <th class="px-6 py-4 font-black text-black uppercase tracking-wider">Fee Summary</th>
                        <th class="px-6 py-4 font-black text-black uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 font-black text-black uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-11 w-11 relative">
                                    @if($student->photo)
                                        <img class="h-11 w-11 rounded-full object-cover border-2 border-white shadow-sm" src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                    @else
                                        <div class="h-11 w-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-sm">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                    @endif
                                    @if($student->after_placement_fee)
                                        <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-600 border-2 border-white shadow-sm" title="After Placement Fee Enabled"></span>
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="font-extrabold text-gray-900 text-base">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                        <span class="bg-gray-100 text-gray-600 py-0.5 px-1.5 rounded mr-2 font-mono">{{ $student->roll_number }}</span>
                                        <span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $student->mobile }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-gray-900 font-semibold">{{ $student->course->name ?? '-' }}</div>
                            <div class="text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full inline-block mt-1 uppercase font-bold tracking-tighter">{{ $student->batch->name ?? 'No Batch' }}</div>
                            <div class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-bold">{{ $student->branch->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $training   = $student->training_fee ?? 0;
                                $placement  = $student->after_placement_amount ?? 0;
                                $discount   = $student->discount ?? 0;
                                $payable    = $training - $discount;
                                if ($student->after_placement_fee && $student->status === 'placed') {
                                    $payable += $placement;
                                }
                                $payable    = max(0, $payable);
                                $paid       = $student->payments_sum_amount ?? 0;
                                $balance    = max(0, $payable - $paid);
                                $credit     = max(0, $paid - $payable);
                            @endphp
                            <div class="flex flex-col">
                                @if($balance > 0)
                                    <span class="text-gray-900 font-bold">Due: ₹{{ number_format($balance, 2) }}</span>
                                @else
                                    <span class="text-green-600 font-bold">Paid ✓</span>
                                @endif
                                @if($credit > 0)
                                    <span class="text-xs text-green-600 font-bold mt-0.5">Credit: ₹{{ number_format($credit, 2) }}</span>
                                @endif
                                <span class="text-xs text-gray-400 line-through mt-0.5">Total: ₹{{ number_format(($training + $placement), 2) }}</span>
                                @if($discount > 0)
                                    <span class="text-[10px] text-green-600 font-bold">Discount: -₹{{ number_format($discount, 2) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $statusColors = [
                                    'admission_done' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'ongoing' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'placed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'dropped' => 'bg-gray-100 text-gray-800 border-gray-200',
                                ];
                                $color = $statusColors[$student->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $color }} uppercase tracking-wide">
                                {{ str_replace('_', ' ', $student->status) }}
                            </span>
                            @if($student->after_placement_fee)
                                <div class="mt-1">
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-extrabold rounded border border-blue-100 uppercase tracking-tighter">After Placement Fee</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-lg transition" title="View Profile">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="text-amber-600 hover:text-amber-900 bg-amber-50 p-2 rounded-lg transition" title="Edit Student">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('WARNING: Are you sure you want to permanently delete this student record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 bg-rose-50 p-2 rounded-lg transition" title="Delete Student">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span class="text-lg font-medium">No students found matches your criteria.</span>
                                <a href="{{ route('students.index') }}" class="mt-2 text-indigo-600 font-bold hover:underline">Clear all filters</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out forwards; }
</style>
@endsection
