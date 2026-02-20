@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="container mx-auto px-6 py-8">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-10">
        <div>
            <h3 class="text-gray-700 text-3xl font-medium">Reports</h3>
            <p class="text-gray-500 mt-1">Select a report to view detailed analysis and exports.</p>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Fee Collection Report -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="h-3 bg-green-500"></div>
            <div class="p-8">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Fee Collection</h4>
                <p class="text-gray-500 mb-8 leading-relaxed">Detailed report of all payments collected. Filter by date range, branch, or specific batch.</p>
                <a href="{{ route('reports.fee-collection') }}" class="inline-flex items-center px-6 py-3 bg-green-50 text-green-700 font-bold rounded-xl hover:bg-green-600 hover:text-white transition-all duration-300">
                    View Report
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Due Fees Report -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="h-3 bg-red-500"></div>
            <div class="p-8">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Due Fees</h4>
                <p class="text-gray-500 mb-8 leading-relaxed">Monitor outstanding balances and pending fees. Identify students with critical payment delays.</p>
                <a href="{{ route('reports.due-report') }}" class="inline-flex items-center px-6 py-3 bg-red-50 text-red-700 font-bold rounded-xl hover:bg-red-600 hover:text-white transition-all duration-300">
                    View Report
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Attendance Report -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="h-3 bg-blue-500"></div>
            <div class="p-8">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Attendance</h4>
                <p class="text-gray-500 mb-8 leading-relaxed">Analyze student attendance trends. View average percentages and monthly summaries by batch.</p>
                <a href="{{ route('reports.attendance-report') }}" class="inline-flex items-center px-6 py-3 bg-blue-50 text-blue-700 font-bold rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300">
                    View Report
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Counsellor Performance -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="h-3 bg-purple-500"></div>
            <div class="p-8">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Counsellor Performance</h4>
                <p class="text-gray-500 mb-8 leading-relaxed">Evaluate productivity across counsellors. Track lead assignments and successful conversion rates.</p>
                <a href="{{ route('reports.counsellor-performance') }}" class="inline-flex items-center px-6 py-3 bg-purple-50 text-purple-700 font-bold rounded-xl hover:bg-purple-600 hover:text-white transition-all duration-300">
                    View Report
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Source Matrix -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <div class="h-3 bg-amber-500"></div>
            <div class="p-8">
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-3">Source Matrix</h4>
                <p class="text-gray-500 mb-8 leading-relaxed">Discover where your leads are coming from. Compare conversion performance across different marketing channels.</p>
                <a href="{{ route('reports.source-report') }}" class="inline-flex items-center px-6 py-3 bg-amber-50 text-amber-700 font-bold rounded-xl hover:bg-amber-600 hover:text-white transition-all duration-300">
                    View Report
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
