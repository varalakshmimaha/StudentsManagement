@extends('layouts.admin')

@section('title', 'Follow-ups Board')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Follow-ups Board</h3>
        <a href="{{ route('leads.create') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">New Lead</a>
    </div>

    <!-- Tabs Header -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button onclick="openTab('overdue')" id="tab-overdue" class="tab-btn py-4 px-1 border-b-2 border-red-500 font-medium text-sm text-red-600">
                Overdue ({{ $overdue->count() }})
            </button>
            <button onclick="openTab('today')" id="tab-today" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Today ({{ $todayFollowups->count() }})
            </button>
            <button onclick="openTab('upcoming')" id="tab-upcoming" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Upcoming
            </button>
        </nav>
    </div>

    <!-- Overdue Section -->
    <div id="content-overdue" class="tab-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($overdue as $lead)
            <x-followup-card :lead="$lead" color="red" />
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg border-2 border-dashed">
                <p class="text-gray-500">No overdue follow-ups! Great job.</p>
            </div>
        @endforelse
    </div>

    <!-- Today Section -->
    <div id="content-today" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($todayFollowups as $lead)
            <x-followup-card :lead="$lead" color="blue" />
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg border-2 border-dashed">
                <p class="text-gray-500">No follow-ups scheduled for today.</p>
            </div>
        @endforelse
    </div>

    <!-- Upcoming Section -->
    <div id="content-upcoming" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($upcoming as $lead)
            <x-followup-card :lead="$lead" color="gray" />
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg border-2 border-dashed">
                <p class="text-gray-500">No upcoming follow-ups found.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function openTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-red-500', 'text-red-600', 'border-indigo-500', 'text-indigo-600', 'border-gray-500', 'text-gray-600');
            el.classList.add('border-transparent', 'text-gray-500');
        });

        const content = document.getElementById('content-' + tabName);
        const btn = document.getElementById('tab-' + tabName);
        
        content.classList.remove('hidden');
        btn.classList.remove('border-transparent', 'text-gray-500');
        
        if(tabName === 'overdue') btn.classList.add('border-red-500', 'text-red-600');
        else if(tabName === 'today') btn.classList.add('border-blue-500', 'text-blue-600');
        else btn.classList.add('border-indigo-500', 'text-indigo-600');
    }
</script>
@endsection
