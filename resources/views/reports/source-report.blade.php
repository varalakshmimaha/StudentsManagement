@extends('layouts.admin')

@section('title', 'Lead Source Metrics')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 text-3xl font-medium">Lead Source Analysis</h3>
        <div class="flex space-x-2">
            <a href="{{ route('reports.export-source-report') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-bold flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export CSV
            </a>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Source
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Converted
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Conv. Rate
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats as $row)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $row->source ?: 'Unknown' }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $row->total_leads }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded font-semibold">{{ $row->converted_leads }}</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @php
                                $rate = $row->total_leads > 0 ? ($row->converted_leads / $row->total_leads) * 100 : 0;
                            @endphp
                            <div class="flex items-center">
                                <span class="mr-2">{{ number_format($rate, 1) }}%</span>
                                <div class="relative w-full">
                                    <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                        <div style="width:{{ $rate }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-5 text-center text-gray-500">No data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Chart Visualization (Simple JS) -->
        <div class="bg-white p-6 rounded-lg shadow h-fit">
            <h4 class="text-lg font-bold text-gray-700 mb-4">Lead Distribution by Source</h4>
            <div class="h-64">
                <canvas id="sourceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stats = @json($stats);
        const ctx = document.getElementById('sourceChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: stats.map(s => s.source || 'Unknown'),
                datasets: [{
                    data: stats.map(s => s.total_leads),
                    backgroundColor: [
                        '#6366F1', '#10B981', '#F59E0B', '#EF4444', '#EC4899', '#8B5CF6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endsection
