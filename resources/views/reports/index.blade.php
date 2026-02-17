@extends('layouts.admin')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h3 class="text-gray-700 text-3xl font-medium">Reports Dashboard</h3>
        
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 items-center w-full md:w-auto">
            <!-- Branch Filter -->
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center space-x-2 w-full md:w-auto">
                <select name="branch_id" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-900 px-4 py-2 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full md:w-auto">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </form>

            <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm text-sm font-medium hover:bg-indigo-500 flex items-center print:hidden w-full md:w-auto justify-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Export PDF
            </button>
        </div>
    </div>

    <!-- 1. KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Students -->
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500 relative overflow-hidden">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Students</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1"><span class="counter-value" data-target="{{ $studentsCount }}">0</span></h4>
                    <div class="flex items-center mt-2">
                        @if($studentTrend >= 0)
                            <span class="text-green-500 text-sm font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                {{ number_format($studentTrend, 1) }}%
                            </span>
                        @else
                             <span class="text-red-500 text-sm font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                {{ number_format(abs($studentTrend), 1) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 text-xs ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Active Batches -->
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-purple-500 relative overflow-hidden">
             <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Active Batches</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1"><span class="counter-value" data-target="{{ $activeBatches }}">0</span></h4>
                    <div class="flex items-center mt-2">
                        @if($batchesTrend >= 0)
                             <span class="text-green-500 text-sm font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                {{ number_format($batchesTrend, 1) }}%
                            </span>
                        @else
                            <span class="text-red-500 text-sm font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                {{ number_format(abs($batchesTrend), 1) }}%
                            </span>
                        @endif
                        <span class="text-gray-400 text-xs ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <!-- Collected This Month -->
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500 relative overflow-hidden">
             <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Collected (Month)</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1">₹<span class="counter-value" data-target="{{ $collectedThisMonth }}">0</span></h4>
                    <div class="flex items-center mt-2">
                         @if($collectionTrend >= 0)
                             <span class="text-green-500 text-sm font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                {{ number_format($collectionTrend, 1) }}%
                            </span>
                        @else
                            <span class="text-red-500 text-sm font-semibold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                {{ number_format(abs($collectionTrend), 1) }}%
                            </span>
                        @endif
                         <span class="text-gray-400 text-xs ml-2">vs last month</span>
                    </div>
                </div>
                <div class="p-3 bg-green-100 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total Due -->
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-red-500 relative overflow-hidden">
             <div class="flex justify-between items-start">
                <div>
                     <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Due</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1">₹<span class="counter-value" data-target="{{ $totalDue }}">0</span></h4>
                    <div class="flex items-center mt-2">
                         <span class="text-red-500 text-sm font-semibold">Critical</span>
                         <span class="text-gray-400 text-xs ml-2">outstanding</span>
                    </div>
                </div>
                <div class="p-3 bg-red-100 rounded-lg text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Collection Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h4 class="text-lg font-bold text-gray-700 mb-4">Monthly Collection</h4>
            <div class="h-64">
                <canvas id="collectionChart"></canvas>
            </div>
        </div>

        <!-- Attendance Chart -->
         <div class="bg-white p-6 rounded-xl shadow-sm">
            <h4 class="text-lg font-bold text-gray-700 mb-4">Attendance Trend (Avg %)</h4>
             <div class="h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Lead Conversion Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm lg:col-span-2">
            <h4 class="text-lg font-bold text-gray-700 mb-4">Lead Conversion Trend (Last 6 Months)</h4>
            <div class="h-64">
                <canvas id="leadConversionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 3. Financial Summary -->
    <div class="mb-8">
        <h4 class="text-lg font-bold text-gray-700 mb-4">Financial Summary</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">Today's Collection</p>
                    <p class="text-xl font-bold text-gray-800">₹{{ number_format($todayCollection) }}</p>
                </div>
                <div class="h-8 w-8 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            
             <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">This Week</p>
                    <p class="text-xl font-bold text-gray-800">₹{{ number_format($weekCollection) }}</p>
                </div>
                 <div class="h-8 w-8 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>

             <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase">This Month</p>
                    <p class="text-xl font-bold text-gray-800">₹{{ number_format($collectedThisMonth) }}</p>
                </div>
                 <div class="h-8 w-8 bg-purple-50 rounded-full flex items-center justify-center text-purple-500">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 4. Quick Reports Access -->
    <div class="mb-8">
        <h4 class="text-lg font-bold text-gray-700 mb-4">Reports Quick Access</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Fee Report Card -->
            <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition-shadow duration-300 group">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-green-100 text-green-600 rounded-full mr-3 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h5 class="font-bold text-gray-800">Fee Collection</h5>
                </div>
                <p class="text-sm text-gray-500 mb-4 h-10">Track payments by date, branch & batch.</p>
                <a href="{{ route('reports.fee-collection') }}" class="text-sm font-semibold text-green-600 hover:text-green-800 flex items-center">
                    View Report <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <!-- Due Report Card -->
            <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition-shadow duration-300 group">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-red-100 text-red-600 rounded-full mr-3 group-hover:bg-red-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h5 class="font-bold text-gray-800">Due Fees</h5>
                </div>
                <p class="text-sm text-gray-500 mb-4 h-10">List of students with outstanding balances.</p>
                <a href="{{ route('reports.due-report') }}" class="text-sm font-semibold text-red-600 hover:text-red-800 flex items-center">
                    View Report <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <!-- Attendance Report Card -->
            <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition-shadow duration-300 group">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-full mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h5 class="font-bold text-gray-800">Attendance</h5>
                </div>
                 <p class="text-sm text-gray-500 mb-4 h-10">Monthly attendance summary per batch.</p>
                <a href="{{ route('reports.attendance-report') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center">
                    View Report <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <!-- Counsellor Performance Card -->
            <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition-shadow duration-300 group">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-full mr-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h5 class="font-bold text-gray-800">Counsellor Perf.</h5>
                </div>
                 <p class="text-sm text-gray-500 mb-4 h-10">Lead assignment and conversion metrics.</p>
                <a href="{{ route('reports.counsellor-performance') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-800 flex items-center">
                    View Report <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <!-- Source Report Card -->
            <div class="bg-white rounded-xl p-5 shadow-sm border hover:shadow-md transition-shadow duration-300 group">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-amber-100 text-amber-600 rounded-full mr-3 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </div>
                    <h5 class="font-bold text-gray-800">Source Metrics</h5>
                </div>
                 <p class="text-sm text-gray-500 mb-4 h-10">Lead counts and conversion by source.</p>
                <a href="{{ route('reports.source-report') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-800 flex items-center">
                    View Report <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- 5. Top Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Top 5 Highest Due -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
             <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h5 class="text-gray-800 font-bold">⚠️ Top Students with Highest Due</h5>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b">
                        <th class="px-6 py-3 font-semibold">Student</th>
                        <th class="px-6 py-3 font-semibold">Batch</th>
                        <th class="px-6 py-3 font-semibold text-right">Due</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topDueStudents as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $student->name }}</td>
                         <td class="px-6 py-4 text-sm text-gray-600">{{ $student->batch->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-red-600 text-right">₹{{ number_format($student->due_amount) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Top 5 Batches by Collection -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
             <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h5 class="text-gray-800 font-bold">🏆 Top Batches by Collection</h5>
            </div>
             <table class="w-full text-left">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b">
                        <th class="px-6 py-3 font-semibold">Batch</th>
                        <th class="px-6 py-3 font-semibold text-right">Collected</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($topBatches as $batch)
                     <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $batch->name }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600 text-right">₹{{ number_format($batch->collected) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500">No data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts for Charts and Counters -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Animated Counters Logic
        const counters = document.querySelectorAll('.counter-value');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 1500; // ms
            const startTime = performance.now();
            
            function updateCount(timestamp) {
                const elapsed = timestamp - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const currentVal = Math.floor(progress * target);
                
                counter.innerText = currentVal.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(updateCount);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            }
            
            requestAnimationFrame(updateCount);
        });

        // Collection Chart Data
        const collectionCtx = document.getElementById('collectionChart').getContext('2d');
        const collectionData = @json($monthlyCollection);
        
        new Chart(collectionCtx, {
            type: 'bar',
            data: {
                labels: collectionData.map(d => d.month),
                datasets: [{
                    label: 'Collection (₹)',
                    data: collectionData.map(d => d.total),
                    backgroundColor: '#10B981', // Tailwind Green-500
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Attendance Chart Data
         const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
         const attendanceData = @json($attendanceTrend);
         
         new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: attendanceData.map(d => d.month),
                datasets: [{
                    label: 'Attendance %',
                    data: attendanceData.map(d => d.percentage),
                    borderColor: '#6366F1', // Indigo 500
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#6366F1',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                 scales: {
                    y: { beginAtZero: true, max: 100, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Lead Conversion Chart Data
        const leadCtx = document.getElementById('leadConversionChart').getContext('2d');
        const leadData = @json($leadConversion);
        
        new Chart(leadCtx, {
            type: 'line',
            data: {
                labels: leadData.map(d => d.month),
                datasets: [
                    {
                        label: 'Total Leads',
                        data: leadData.map(d => d.total_leads),
                        borderColor: '#9CA3AF', // Gray 400
                        tension: 0.1,
                        fill: false
                    },
                    {
                        label: 'Converted',
                        data: leadData.map(d => d.converted_count),
                        borderColor: '#059669', // Emerald 600
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>

<style>
    /* Hover Animation for Quick Reports */
    .group:hover {
        transform: translateY(-5px);
    }
    .group {
        transition: transform 0.3s ease-in-out;
    }

    /* Print Styles */
    @media print {
        .print\:hidden { display: none !important; }
        aside, nav, header { display: none !important; }
        body { background: white !important; }
        .container { max-width: 100% !important; padding: 0 !important; }
        .grid { display: block !important; }
        .grid-cols-1, .md\:grid-cols-2, .lg\:grid-cols-4, .lg\:grid-cols-2 { display: block !important; }
        .mb-8, .mb-6 { page-break-inside: avoid; margin-bottom: 2rem; }
        canvas { max-width: 100% !important; }
    }
</style>
@endsection
