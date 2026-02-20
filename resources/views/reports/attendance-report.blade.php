@extends('layouts.admin')

@section('title', 'Attendance Report')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-gray-800 text-3xl font-extrabold tracking-tight">Attendance Analysis</h3>
            <p class="text-gray-500 text-sm mt-1">Detailed visibility into student presence and batch performance.</p>
        </div>
        <div class="flex gap-2">
             @if($batchId)
                <a href="{{ request()->fullUrlWithQuery(['export' => 1]) }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition shadow-md flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </a>
            @endif
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 mb-8 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
        <form method="GET" action="{{ route('reports.attendance-report') }}" class="relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-end">
                <div class="lg:col-span-1">
                    <label class="block text-xs font-black text-indigo-900 uppercase tracking-widest mb-3">Report Configuration</label>
                    <div class="flex p-1 bg-gray-100 rounded-xl">
                        <label class="flex-1">
                            <input type="radio" name="report_type" value="daily" {{ $reportType == 'daily' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden peer">
                            <div class="py-2.5 text-center text-xs font-bold rounded-lg cursor-pointer transition peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-indigo-600 text-gray-700">Daily</div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="report_type" value="monthly" {{ $reportType == 'monthly' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden peer">
                            <div class="py-2.5 text-center text-xs font-bold rounded-lg cursor-pointer transition peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-indigo-600 text-gray-700">Monthly</div>
                        </label>
                    </div>
                </div>

                <div class="lg:col-span-2 flex items-end gap-4">
                    @if($reportType == 'daily')
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-800 uppercase mb-2">Select Date</label>
                            <input type="date" name="report_date" value="{{ $reportDate }}" class="w-full bg-white border border-gray-300 rounded-xl text-sm py-3 focus:ring-indigo-500 shadow-sm text-gray-900">
                        </div>
                    @else
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-800 uppercase mb-2">Select Month</label>
                            <input type="month" name="report_month" value="{{ $reportMonth }}" class="w-full bg-white border border-gray-300 rounded-xl text-sm py-3 focus:ring-indigo-500 shadow-sm text-gray-900">
                        </div>
                    @endif

                    <div class="hidden lg:flex items-center justify-center px-4 h-12 text-gray-400 font-bold italic">OR</div>

                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-800 uppercase mb-2">Target Batch <span class="text-red-500">*</span></label>
                        <select name="batch_id" required class="w-full bg-white border border-gray-300 rounded-xl text-sm py-3 focus:ring-indigo-500 shadow-sm text-gray-900">
                            <option value="">Choose Batch...</option>
                            @foreach($allBatches as $b)
                                <option value="{{ $b->id }}" {{ $batchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-xl font-black text-sm uppercase tracking-widest hover:bg-black transition transform active:scale-95 shadow-lg">
                        Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($batch && $summary)
        <!-- Summary Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm col-span-1 md:col-span-2 lg:col-span-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Batch Context</p>
                <h4 class="text-lg font-black text-indigo-900 leading-tight mb-1">{{ $summary['batch_name'] }}</h4>
                <p class="text-xs font-bold text-indigo-400">{{ $summary['course'] }} | {{ $summary['branch'] }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm group hover:border-indigo-200 transition">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Students</p>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-black text-gray-900 group-hover:text-indigo-600 transition">{{ $summary['total_students'] }}</span>
                    <div class="h-8 w-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h11v-1a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $reportType == 'daily' ? 'Status' : 'Working Days' }}</p>
                <div class="flex items-end justify-between">
                    @if($reportType == 'daily')
                        <span class="text-lg font-black {{ $summary['total_days'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $summary['total_days'] > 0 ? 'Working Day' : 'Holiday ('.$summary['holiday_name'].')' }}
                        </span>
                    @else
                        <span class="text-3xl font-black text-gray-900">{{ $summary['working_days'] }} <span class="text-xs text-gray-400 ml-1">Days</span></span>
                    @endif
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm bg-gradient-to-br from-emerald-50 to-white">
                <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-2">Presence Ratio</p>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-black text-emerald-800">{{ $summary['present_count'] }} <span class="text-xs text-emerald-500 font-bold">P</span></span>
                    <span class="text-3xl font-black text-rose-800">{{ $summary['absent_count'] }} <span class="text-xs text-rose-500 font-bold">A</span></span>
                </div>
            </div>

            <div class="bg-indigo-600 p-6 rounded-2xl shadow-xl shadow-indigo-100">
                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Attendance Avg</p>
                <div class="text-4xl font-black text-white leading-none">{{ $summary['percentage'] }}%</div>
                <div class="w-full bg-indigo-900/30 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-white h-full" style="width: {{ $summary['percentage'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                <h5 class="font-black text-gray-900 tracking-tight">Student Performance Grid</h5>
                                <div class="flex gap-2 p-1 bg-gray-50 rounded-xl" id="statusFilters">
                    <button onclick="filterStatus('all', this)" id="btn-all" class="status-btn active bg-indigo-600 text-white px-4 py-1.5 text-xs font-bold rounded-lg transition shadow-sm">All</button>
                    <button onclick="filterStatus('Present', this)" class="status-btn text-gray-600 hover:bg-gray-200 hover:text-gray-900 px-4 py-1.5 text-xs font-bold rounded-lg transition">Present</button>
                    <button onclick="filterStatus('Absent', this)" class="status-btn text-gray-600 hover:bg-gray-200 hover:text-gray-900 px-4 py-1.5 text-xs font-bold rounded-lg transition">Absent</button>
                    @if($reportType == 'daily')
                        <button onclick="filterStatus('Not Marked', this)" class="status-btn text-gray-600 hover:bg-gray-200 hover:text-gray-900 px-4 py-1.5 text-xs font-bold rounded-lg transition">Pending</button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]" id="attendanceTable">
                    <thead>
                        <tr class="bg-indigo-50/50 border-b border-indigo-100">
                            <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px] w-16">Sl</th>
                            <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px]">Student Info</th>
                            @if($reportType == 'daily')
                                <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px]">Marking Status</th>
                            @else
                                <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px] text-center">Working</th>
                                <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px] text-center">Present</th>
                                <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px] text-center">Absent</th>
                                <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px] text-center">Avg %</th>
                            @endif
                            <th class="px-8 py-4 font-black text-black uppercase tracking-widest text-[10px] text-right">Activity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($studentAttendance->values() as $index => $s)
                        <tr class="student-row hover:bg-indigo-50/20 transition group" data-status="{{ $reportType == 'daily' ? $s['status'] : ($s['percentage'] >= 75 ? 'Present' : 'Absent') }}">
                            <td class="px-8 py-5 text-sm font-black text-gray-900 group-hover:text-indigo-600">{{ $index + 1 }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 bg-gray-100 rounded-2xl flex items-center justify-center font-black text-gray-400 text-xs mr-4 shadow-inner">
                                        {{ substr($s['name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-gray-900 mb-0.5 group-hover:text-indigo-600">{{ $s['name'] }}</div>
                                        <div class="text-[10px] font-mono font-bold text-gray-600 uppercase tracking-tight">{{ $s['roll_number'] }}</div>
                                    </div>
                                </div>
                            </td>
                            @if($reportType == 'daily')
                                <td class="px-8 py-5">
                                    @php
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                        if($s['status'] == 'Present') $statusClass = 'bg-emerald-100 text-emerald-700';
                                        elseif($s['status'] == 'Absent') $statusClass = 'bg-rose-100 text-rose-700';
                                        elseif(str_contains($s['status'], 'Holiday')) $statusClass = 'bg-amber-100 text-amber-700';
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                        {{ $s['status'] }}
                                    </span>
                                </td>
                            @else
                                <td class="px-8 py-5 text-center text-sm font-bold text-gray-500">{{ $s['working_days'] }}</td>
                                <td class="px-8 py-5 text-center text-sm font-black text-emerald-600">{{ $s['present_days'] }}</td>
                                <td class="px-8 py-5 text-center text-sm font-black text-rose-600">{{ $s['absent_days'] }}</td>
                                <td class="px-8 py-5 text-center">
                                    <span class="text-sm font-black {{ $s['percentage'] >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $s['percentage'] }}%</span>
                                </td>
                            @endif
                            <td class="px-8 py-5 text-right">
                                <button onclick="viewDetail({{ $s['id'] }})" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                    Analyze
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="text-gray-400 font-bold">No students found in this batch.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($batchId)
        <div class="bg-white p-20 rounded-3xl shadow-xl border border-dashed border-gray-200 text-center">
            <div class="inline-flex h-24 w-24 bg-gray-50 rounded-full items-center justify-center mb-6 text-gray-200">
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h4 class="text-xl font-black text-gray-900">No Data Captured</h4>
            <p class="text-gray-400 font-medium max-w-sm mx-auto mt-2">We couldn't find any attendance logs for the selected criteria. Ensure marking is complete for this period.</p>
        </div>
    @else
        <div class="bg-indigo-900 p-20 rounded-3xl shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-indigo-800 opacity-50 group-hover:opacity-40 transition pointer-events-none"></div>
            <div class="relative z-10 text-center">
                <div class="inline-flex h-20 w-20 bg-indigo-500/30 rounded-full items-center justify-center mb-6 text-white border-2 border-indigo-400/50">
                    <svg class="h-10 w-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h4 class="text-2xl font-black text-white tracking-tight">Ready for Generation</h4>
                <p class="text-indigo-200 font-bold max-w-sm mx-auto mt-2 leading-relaxed opacity-80">Pick a batch and timeline above to generate a high-precision attendance analytics report.</p>
            </div>
        </div>
    @endif
</div>

<!-- Detail Modal Container -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-950/60 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto animate-modal-in" id="modalContent">
        <!-- Content will be AJAX loaded -->
    </div>
</div>

<script>
    function filterStatus(status, btn) {
        const rows = document.querySelectorAll('.student-row');
        const btns = document.querySelectorAll('.status-btn');
        
        // Reset all buttons to INACTIVE state
        btns.forEach(b => {
             b.classList.remove('active', 'bg-indigo-600', 'text-white', 'shadow-sm');
             b.classList.add('text-gray-600');
        });

        // Highlight clicked button to ACTIVE state
        if (btn) {
            btn.classList.remove('text-gray-600');
            btn.classList.add('active', 'bg-indigo-600', 'text-white', 'shadow-sm');
        }

        rows.forEach(row => {
            const rowStatus = row.dataset.status || '';
            if (status === 'all' || rowStatus.includes(status)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    // Ensure 'All' filter is active on page load and all rows visible
    document.addEventListener('DOMContentLoaded', function() {
        const allBtn = document.getElementById('btn-all');
        // Ensure initial correct state for 'All'
        if (allBtn) {
            // It already has active classes from HTML, just ensure consistent DOM state
        }
    });

    function viewDetail(studentId) {
        const modal = document.getElementById('detailModal');
        const container = document.getElementById('modalContent');
        const type = '{{ $reportType }}';
        const date = '{{ $reportDate }}';
        const month = '{{ $reportMonth }}';

        modal.classList.remove('hidden');
        container.innerHTML = `
            <div class="p-20 text-center">
                <div class="inline-block animate-spin h-8 w-8 border-4 border-indigo-600 border-t-transparent rounded-full mb-4"></div>
                <p class="font-black text-gray-400 uppercase tracking-widest text-xs">Crunching Data...</p>
            </div>
        `;

        fetch(`/reports/student-attendance/${studentId}?report_type=${type}&date=${date}&month=${month}`)
            .then(res => res.text())
            .then(html => {
                if(type === 'daily') {
                    const data = JSON.parse(html);
                    container.innerHTML = `
                        <div class="p-10">
                            <div class="flex justify-between items-start mb-10">
                                <div>
                                    <h2 class="text-3xl font-black text-gray-900">${data.student.name}</h2>
                                    <p class="text-indigo-500 font-black uppercase text-xs tracking-widest mt-1">${data.student.roll_number}</p>
                                </div>
                                <button onclick="closeModal()" class="h-10 w-10 bg-gray-50 rounded-full flex items-center justify-center hover:bg-gray-100 transition">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-8">
                                <div class="bg-gray-50 p-6 rounded-2xl">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Marked Date</p>
                                    <p class="text-lg font-black text-gray-900">${new Date(data.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-2xl">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Current Status</p>
                                    <p class="text-lg font-black ${data.status === 'Present' ? 'text-emerald-600' : 'text-rose-600'}">${data.status}</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-2xl">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Marked By</p>
                                    <p class="text-lg font-black text-gray-900">${data.marked_by}</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-2xl">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">System Timestamp</p>
                                    <p class="text-lg font-black text-gray-900">${data.marked_at}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    container.innerHTML = html;
                }
            });
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Close on escape
    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') closeModal();
    });
</script>

<style>
    .status-btn.active { background-color: #4f46e5; color: white; }
    @keyframes modal-in { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-modal-in { animation: modal-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection
