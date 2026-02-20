@extends('layouts.admin')

@section('title', 'Holiday Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-gray-800 text-3xl font-extrabold tracking-tight">Holiday Management</h3>
            <p class="text-gray-500 text-sm mt-1">Manage institutional and branch-specific holidays.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-6 py-3 bg-gray-600 text-white rounded-lg font-bold hover:bg-gray-700 transition shadow-md">Import CSV</button>
            <button onclick="document.getElementById('holidayModal').classList.remove('hidden')" class="px-6 py-3 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition shadow-md">Add Holiday</button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-8">
        <form method="GET" action="{{ route('holidays.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-black text-black uppercase mb-2">Year</label>
                <select name="year" class="w-full bg-white border border-gray-300 rounded-lg text-sm text-gray-900 py-2.5 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Years</option>
                    @for($i = date('Y'); $i >= 2024; $i--)
                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-black uppercase mb-2">Month</label>
                <select name="month" class="w-full bg-white border border-gray-300 rounded-lg text-sm text-gray-900 py-2.5 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-black uppercase mb-2">Branch</label>
                <select name="branch_id" class="w-full bg-white border border-gray-300 rounded-lg text-sm text-gray-900 py-2.5 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Branches</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-black uppercase mb-2">Type</label>
                <select name="type" class="w-full bg-white border border-gray-300 rounded-lg text-sm text-gray-900 py-2.5 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Types</option>
                    <option value="General" {{ request('type') == 'General' ? 'selected' : '' }}>General</option>
                    <option value="Branch Specific" {{ request('type') == 'Branch Specific' ? 'selected' : '' }}>Branch Specific</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition shadow-sm">Filter</button>
                <a href="{{ route('holidays.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-lg font-bold hover:bg-gray-200 transition text-center shadow-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-indigo-50/50 border-b border-indigo-100">
                    <th class="px-6 py-4 font-black text-black uppercase tracking-widest text-[10px]">Date</th>
                    <th class="px-6 py-4 font-black text-black uppercase tracking-widest text-[10px]">Title</th>
                    <th class="px-6 py-4 font-black text-black uppercase tracking-widest text-[10px]">Type</th>
                    <th class="px-6 py-4 font-black text-black uppercase tracking-widest text-[10px]">Recurring</th>
                    <th class="px-6 py-4 font-black text-black uppercase tracking-widest text-[10px]">Branch</th>
                    <th class="px-6 py-4 font-black text-black uppercase tracking-widest text-[10px]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($holidays as $holiday)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $holiday->date ? $holiday->date->format('d M Y') : $holiday->month_day . ' (Recurring)' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $holiday->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-[10px] font-extrabold uppercase rounded-full {{ $holiday->type == 'General' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ $holiday->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {!! $holiday->is_recurring ? '<span class="text-green-600 font-bold text-xs">Yes</span>' : '<span class="text-gray-400 text-xs">No</span>' !!}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $holiday->branch->name ?? 'All Branches' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                             <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Delete this holiday?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No holidays found for selected criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 bg-gray-50">
            {{ $holidays->appends(request()->all())->links() }}
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="holidayModal" class="hidden fixed inset-0 bg-gray-950/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">Add New Holiday</h3>
            <button onclick="document.getElementById('holidayModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('holidays.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black text-black uppercase mb-1">Holiday Title</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg text-sm text-gray-900 py-2 px-3 focus:ring-red-500 focus:border-red-500" placeholder="e.g., Independence Day">
            </div>
            <div>
                <label class="block text-xs font-black text-black uppercase mb-1">Date</label>
                <input type="date" name="date" required class="w-full border border-gray-300 rounded-lg text-sm text-gray-900 py-2 px-3 focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-black uppercase mb-1">Type</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg text-sm text-gray-900 py-2 px-3 focus:ring-red-500 focus:border-red-500">
                        <option value="General">General</option>
                        <option value="Branch Specific">Branch Specific</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-black uppercase mb-1">Branch</label>
                    <select name="branch_id" class="w-full border border-gray-300 rounded-lg text-sm text-gray-900 py-2 px-3 focus:ring-red-500 focus:border-red-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-4 py-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_recurring" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <span class="text-sm font-medium text-gray-700">Recurring (Every Year)</span>
                </label>
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="document.getElementById('holidayModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold">Cancel</button>
                <button type="submit" class="flex-2 px-6 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700">Save Holiday</button>
            </div>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-950/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">Import Holidays</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('holidays.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-black text-black uppercase mb-1">Select CSV File</label>
                <input type="file" name="csv_file" required class="w-full border border-gray-300 rounded-lg text-sm text-gray-900 py-2 px-3 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-[10px] text-gray-400 mt-2 font-medium">Format: date,title,type,branch_id,is_recurring</p>
                <p class="text-[10px] text-gray-400 font-medium">e.g., 2026-08-15,Independence Day,General,,1</p>
            </div>
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold">Cancel</button>
                <button type="submit" class="flex-2 px-6 py-2 bg-gray-800 text-white rounded-lg font-bold hover:bg-black">Upload & Import</button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fade-in { 0% { opacity: 0; transform: scale(0.95); } 100% { opacity: 1; transform: scale(1); } }
    .animate-fade-in { animation: fade-in 0.2s ease-out forwards; }
</style>
@endsection
