@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h3 class="text-gray-800 text-3xl font-extrabold tracking-tight">Attendance Marking</h3>
            <p class="text-gray-500 text-sm mt-1">Track daily student presence and manage batch attendance.</p>
        </div>
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

    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
        <form method="GET" action="{{ route('attendances.index') }}" id="selectionForm" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Branch</label>
                <select name="branch_id" class="block w-full bg-white border border-gray-400 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 shadow-sm text-gray-900">
                    <option value="" class="text-gray-500">Select Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }} class="text-gray-900">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Batch</label>
                <select name="batch_id" class="block w-full bg-white border border-gray-400 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 shadow-sm text-gray-900">
                    <option value="" class="text-gray-500">Select Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $selectedBatch == $batch->id ? 'selected' : '' }} class="text-gray-900">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Date</label>
                <input type="date" name="date" value="{{ $selectedDate }}" class="block w-full bg-white border border-gray-400 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 shadow-sm text-gray-900">
            </div>
            <div class="flex gap-3">
                 <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-lg shadow transition-colors">
                    Filter
                 </button>
                 <a href="{{ route('attendances.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2.5 px-4 rounded-lg shadow text-center transition-colors">
                    Reset
                 </a>
            </div>
        </form>
    </div>

    @if($isSunday)
        <div class="mt-8 bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-6 rounded-xl shadow-sm flex items-center">
            <div class="h-12 w-12 bg-rose-100 rounded-full flex items-center justify-center mr-4 text-rose-500">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
                <p class="font-extrabold text-xl">Sunday Holiday</p>
                <p class="text-sm text-rose-600 opacity-80">Attendance marking is disabled for Sundays.</p>
            </div>
        </div>
    @elseif($holiday)
        <div class="mt-8 bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-6 rounded-xl shadow-sm flex items-center">
            <div class="h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center mr-4 text-amber-500">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
            </div>
            <div>
                <p class="font-extrabold text-xl">{{ $holiday->name }}</p>
                <p class="text-sm text-amber-600 opacity-80">This date is marked as a {{ $holiday->branch_id ? 'branch-specific' : 'general' }} holiday.</p>
            </div>
        </div>
    @elseif($selectedBatch && $students->isNotEmpty())
        <div class="mt-8">
            <form action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $selectedBatch }}">
                <input type="hidden" name="date" value="{{ $selectedDate }}">

                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white mr-3 shadow-md">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-indigo-900 font-extrabold uppercase tracking-tight">{{ $batches->where('id', $selectedBatch)->first()->name }}</h4>
                            <p class="text-xs text-indigo-500 font-bold">{{ $students->count() }} active students enrolled</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="markAll('present')" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold shadow transition">
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mark All Present
                        </button>
                        <button type="button" onclick="markAll('absent')" class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-bold shadow transition">
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Mark All Absent
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest text-[10px] w-16">Sl</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest text-[10px]">Student Details</th>
                                <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest text-[10px] text-center">Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($students as $index => $student)
                            <tr class="hover:bg-indigo-50/30 transition group">
                                <td class="px-6 py-5 text-sm font-bold text-gray-300 group-hover:text-indigo-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 font-bold text-xs mr-3 shadow-inner">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-extrabold text-gray-900 leading-none mb-1 group-hover:text-indigo-900">{{ $student->name }}</div>
                                            <div class="text-[10px] font-mono font-bold text-gray-400 uppercase tracking-tighter">{{ $student->roll_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center items-center space-x-2">
                                        <label class="flex-1 max-w-[120px]">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="present" 
                                                {{ (isset($markedAttendance[$student->id]) && $markedAttendance[$student->id] == 'present') ? 'checked' : '' }}
                                                class="attendance-radio hidden peer">
                                            <div class="cursor-pointer py-2 px-4 text-center rounded-lg border-2 border-gray-100 bg-gray-50 text-gray-400 font-bold text-xs uppercase tracking-wide peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition shadow-sm hover:border-emerald-200">
                                                Present
                                            </div>
                                        </label>
                                        <label class="flex-1 max-w-[120px]">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="absent" 
                                                {{ (isset($markedAttendance[$student->id]) && $markedAttendance[$student->id] == 'absent') ? 'checked' : '' }}
                                                class="attendance-radio hidden peer">
                                            <div class="cursor-pointer py-2 px-4 text-center rounded-lg border-2 border-gray-100 bg-gray-50 text-gray-400 font-bold text-xs uppercase tracking-wide peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-600 transition shadow-sm hover:border-rose-200">
                                                Absent
                                            </div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-xl shadow-xl transform transition hover:-translate-y-1 active:scale-95">
                        Submit Attendance Records
                    </button>
                </div>
            </form>
        </div>
    @elseif($selectedBatch && $students->isEmpty())
        <div class="mt-8 p-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <div class="inline-flex h-20 w-20 bg-gray-100 rounded-full items-center justify-center mb-4 text-gray-300">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <h3 class="text-lg font-extrabold text-gray-900">No students found</h3>
            <p class="text-sm text-gray-500 mt-2">There are no active students enrolled in this batch currently.</p>
        </div>
    @else
        <div class="mt-8 p-16 text-center bg-indigo-50/50 rounded-2xl border-2 border-dashed border-indigo-200">
            <div class="inline-flex h-20 w-20 bg-indigo-100 rounded-full items-center justify-center mb-4 text-indigo-400 border-4 border-indigo-50 shadow-inner">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-lg font-extrabold text-indigo-900">Configure Marking Criteria</h3>
            <p class="text-sm text-indigo-500 mt-2">Pick a Branch, Batch and Date above to load the roster and start tracking attendance.</p>
        </div>
    @endif
</div>

<script>
    function markAll(status) {
        const radios = document.querySelectorAll(`.attendance-radio[value="${status}"]`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }

    // Dynamic Batch dropdown based on Branch selection
    const branchSelect = document.querySelector('select[name="branch_id"]');
    const batchSelect = document.querySelector('select[name="batch_id"]');

    if (branchSelect && batchSelect) {
        branchSelect.addEventListener('change', function () {
            const branchId = this.value;
            // Reset batch dropdown
            batchSelect.innerHTML = '<option value="">Loading...</option>';

            if (!branchId) {
                batchSelect.innerHTML = '<option value="">Select Batch</option>';
                return;
            }

            fetch(`/batches?branch_id=${branchId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                batchSelect.innerHTML = '<option value="" class="text-gray-500">Select Batch</option>';
                if (data.length === 0) {
                    batchSelect.innerHTML += '<option disabled>No batches found</option>';
                } else {
                    data.forEach(batch => {
                        const selected = (batch.id == {{ $selectedBatch ?? 'null' }}) ? 'selected' : '';
                        batchSelect.innerHTML += `<option value="${batch.id}" class="text-gray-900" ${selected}>${batch.name}</option>`;
                    });
                }
            })
            .catch(() => {
                batchSelect.innerHTML = '<option value="">Error loading batches</option>';
            });
        });
    }
</script>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out forwards; }
</style>
@endsection
