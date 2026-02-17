@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h3 class="text-gray-700 text-3xl font-medium">Dashboard</h3>
        <div class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</div>
    </div>

    <!-- TOP ROW - SUMMARY CARDS (4 per row) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Students -->
        <a href="{{ route('students.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
                    <p class="text-xs text-green-600 mt-2">+{{ $newStudentsThisWeek }} new this week</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Card 2: Active Batches -->
        <a href="{{ route('batches.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Batches</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $activeBatches }}</p>
                    <p class="text-xs text-gray-500 mt-2">Upcoming: {{ $upcomingBatches }} | Completed: {{ $completedBatches }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Card 3: Fees Collected (This Month) -->
        <a href="{{ route('reports.fee-collection') }}" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Fees Collected</p>
                    <p class="text-3xl font-bold text-gray-800">₹{{ number_format($feesCollectedThisMonth, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-2">Last month: ₹{{ number_format($feesCollectedLastMonth, 0) }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Card 4: Total Due -->
        <a href="{{ route('reports.due-report') }}" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Due</p>
                    <p class="text-3xl font-bold text-gray-800">₹{{ number_format($totalDue, 0) }}</p>
                    <p class="text-xs text-red-600 mt-2">Due students: {{ $studentsWithDue }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Card 5: Today Attendance -->
        <a href="{{ route('reports.attendance-report') }}" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Today Attendance</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $attendancePercentage }}%</p>
                    <p class="text-xs text-gray-500 mt-2">Present: {{ $presentToday }} | Absent: {{ $absentToday }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Card 6: Leads Pipeline -->
        <a href="{{ route('leads.index') }}" class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Follow-ups Today</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $followupsToday }}</p>
                    <p class="text-xs text-gray-500 mt-2">Overdue: <span class="text-red-600">{{ $overdueFollowups }}</span> | Converted: {{ $convertedThisMonth }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    </div>

    <!-- SECOND ROW - CHARTS -->
    <div class="grid grid-cols-1 gap-6 mb-8">
        <!-- Chart 1: Monthly Fee Collection -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Monthly Fee Collection ({{ now()->year }})</h4>
            <div style="position: relative; height: 300px;">
                <canvas id="feeCollectionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Monthly Fee Collection Chart
    const ctx = document.getElementById('feeCollectionChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Fee Collection (₹)',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
